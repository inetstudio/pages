<?php

namespace InetStudio\Pages\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use InetStudio\Tags\Models\TagModel;
use InetStudio\Pages\Models\PageModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use InetStudio\Pages\Requests\SavePageRequest;
use InetStudio\Categories\Models\CategoryModel;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Pages\Transformers\PageTransformer;

/**
 * Контроллер для управления страницами.
 *
 * Class ContestByTagStatusesController
 */
class PagesController extends Controller
{
    /**
     * Список страниц.
     *
     * @param Datatables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Datatables $dataTable)
    {
        $table = $dataTable->getHtmlBuilder();

        $table->columns($this->getColumns());
        $table->ajax($this->getAjaxOptions());
        $table->parameters($this->getTableParameters());

        return view('admin.module.pages::pages.index', compact('table'));
    }

    /**
     * Свойства колонок datatables.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            ['data' => 'title', 'name' => 'title', 'title' => 'Заголовок'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
        ];
    }

    /**
     * Свойства ajax datatables.
     *
     * @return array
     */
    private function getAjaxOptions()
    {
        return [
            'url' => route('back.pages.data'),
            'type' => 'POST',
            'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
        ];
    }

    /**
     * Свойства datatables.
     *
     * @return array
     */
    private function getTableParameters()
    {
        return [
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => [
                'url' => asset('admin/js/plugins/datatables/locales/russian.json'),
            ],
        ];
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
     */
    public function data()
    {
        $items = PageModel::query();

        return Datatables::of($items)
            ->setTransformer(new PageTransformer)
            ->escapeColumns(['actions'])
            ->make();
    }

    /**
     * Добавление страницы.
     *
     * @param Datatables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Datatables $dataTable)
    {
        $table = $dataTable->getHtmlBuilder();

        $table->columns($this->getColumns('products'));
        $table->ajax($this->getAjaxOptions('products', 'embedded'));
        $table->parameters($this->getTableParameters());

        $categories = CategoryModel::getTree();

        return view('admin.module.pages::pages.form', [
            'item' => new PageModel(),
            'categories' => $categories,
            'productsTable' => $table,
        ]);
    }

    /**
     * Создание страницы.
     *
     * @param SavePageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SavePageRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Редактирование страницы.
     *
     * @param Datatables $dataTable
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Datatables $dataTable, $id = null)
    {
        if (! is_null($id) && $id > 0 && $item = PageModel::find($id)) {
            $categories = CategoryModel::getTree();

            $table = $dataTable->getHtmlBuilder();

            $table->columns($this->getColumns('products'));
            $table->ajax($this->getAjaxOptions('products', 'embedded'));
            $table->parameters($this->getTableParameters());

            return view('admin.module.pages::pages.form', [
                'item' => $item,
                'categories' => $categories,
                'productsTable' => $table,
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * Обновление страницы.
     *
     * @param SavePageRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SavePageRequest $request, $id = null)
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение страницы.
     *
     * @param SavePageRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0 && $item = PageModel::find($id)) {
            $action = 'отредактирована';
        } else {
            $action = 'создана';
            $item = new PageModel();
        }

        $item->title = strip_tags($request->get('title'));
        $item->slug = strip_tags($request->get('slug'));
        $item->description = strip_tags($request->input('description.text'));
        $item->content = $request->input('content.text');
        $item->save();

        $this->saveMeta($item, $request);
        $this->saveCategories($item, $request);
        $this->saveTags($item, $request);
        $this->saveImages($item, $request, ['og_image', 'preview', 'content']);

        Session::flash('success', 'Страница «'.$item->title.'» успешно '.$action);

        return redirect()->to(route('back.pages.edit', $item->fresh()->id));
    }

    /**
     * Сохраняем мета теги.
     *
     * @param PageModel $item
     * @param SavePageRequest $request
     */
    private function saveMeta($item, $request)
    {
        if ($request->has('meta')) {
            foreach ($request->get('meta') as $key => $value) {
                $item->updateMeta($key, $value);
            }
        }
    }

    /**
     * Сохраняем категории.
     *
     * @param PageModel $item
     * @param SavePageRequest $request
     */
    private function saveCategories($item, $request)
    {
        if ($request->has('categories')) {
            $categories = explode(',', $request->get('categories'));
            $item->recategorize(CategoryModel::whereIn('id', $categories)->get());
        } else {
            $item->uncategorize($item->categories);
        }
    }

    /**
     * Сохраняем теги.
     *
     * @param PageModel $item
     * @param SavePageRequest $request
     */
    private function saveTags($item, $request)
    {
        if ($request->has('tags')) {
            $item->syncTags(TagModel::whereIn('id', (array) $request->get('tags'))->get());
        } else {
            $item->detachTags($item->tags);
        }
    }

    /**
     * Сохраняем изображения.
     *
     * @param PageModel $item
     * @param SavePageRequest $request
     * @param array $images
     */
    private function saveImages($item, $request, $images)
    {
        foreach ($images as $name) {
            $properties = $request->get($name);

            if (isset($properties['images'])) {
                $item->clearMediaCollectionExcept($name, $properties['images']);

                foreach ($properties['images'] as $image) {
                    if ($image['id']) {
                        $media = $item->media->find($image['id']);
                        $media->custom_properties = $image['properties'];
                        $media->save();
                    } else {
                        $filename = $image['filename'];

                        $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image['tempname'];

                        $media = $item->addMedia($file)
                            ->withCustomProperties($image['properties'])
                            ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                            ->usingFileName($image['tempname'])
                            ->toMediaCollection($name, 'pages');
                    }

                    $item->update([
                        $name => str_replace($image['src'], '/img/' . $media->id, $item[$name]),
                    ]);
                }
            } else {
                $manipulations = [];

                if (isset($properties['crop']) and config('pages.images.conversions')) {
                    foreach ($properties['crop'] as $key => $cropJSON) {
                        $cropData = json_decode($cropJSON, true);

                        foreach (config('pages.images.conversions.'.$name.'.'.$key) as $conversion) {
                            $manipulations[$conversion['name']] = [
                                'manualCrop' => implode(',', [
                                    round($cropData['width']),
                                    round($cropData['height']),
                                    round($cropData['x']),
                                    round($cropData['y']),
                                ]),
                            ];
                        }
                    }
                }

                if (isset($properties['tempname']) && isset($properties['filename'])) {
                    $image = $properties['tempname'];
                    $filename = $properties['filename'];

                    $item->clearMediaCollection($name);

                    array_forget($properties, ['tempname', 'temppath', 'filename']);
                    $properties = array_filter($properties);

                    $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image;

                    $media = $item->addMedia($file)
                        ->withCustomProperties($properties)
                        ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                        ->usingFileName($image)
                        ->toMediaCollection($name, 'pages');

                    $media->manipulations = $manipulations;
                    $media->save();

                } else {
                    $properties = array_filter($properties);

                    $media = $item->getFirstMedia($name);

                    if ($media) {
                        $media->custom_properties = $properties;
                        $media->manipulations = $manipulations;
                        $media->save();
                    }
                }
            }
        }
    }

    /**
     * Удаление страницы.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null)
    {
        if (! is_null($id) && $id > 0 && $item = PageModel::find($id)) {
            $item->delete();

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlug(Request $request)
    {
        $name = $request->get('name');
        $slug = SlugService::createSlug(PageModel::class, 'slug', $name);

        return response()->json($slug);
    }
}

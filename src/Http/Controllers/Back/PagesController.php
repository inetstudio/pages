<?php

namespace InetStudio\Pages\Http\Controllers\Back;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use InetStudio\Pages\Models\PageModel;
use Illuminate\Support\Facades\Session;
use InetStudio\Pages\Events\ModifyPageEvent;
use InetStudio\Categories\Models\CategoryModel;
use InetStudio\Pages\Transformers\PageTransformer;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Pages\Http\Requests\Back\SavePageRequest;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;
use InetStudio\Meta\Http\Controllers\Back\Traits\MetaManipulationsTrait;
use InetStudio\Tags\Http\Controllers\Back\Traits\TagsManipulationsTrait;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\ImagesManipulationsTrait;
use InetStudio\Categories\Http\Controllers\Back\Traits\CategoriesManipulationsTrait;

/**
 * Контроллер для управления страницами.
 *
 * Class ContestByTagStatusesController
 */
class PagesController extends Controller
{
    use DatatablesTrait;
    use MetaManipulationsTrait;
    use TagsManipulationsTrait;
    use ImagesManipulationsTrait;
    use CategoriesManipulationsTrait;

    /**
     * Список страниц.
     *
     * @param DataTables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(DataTables $dataTable): View
    {
        $table = $this->generateTable($dataTable, 'pages', 'index');

        return view('admin.module.pages::back.pages.index', compact('table'));
    }

    /**
     * DataTables ServerSide.
     *
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $items = PageModel::query();

        return DataTables::of($items)
            ->setTransformer(new PageTransformer)
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * Добавление страницы.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        $categories = CategoryModel::getTree();

        return view('admin.module.pages::back.pages.form', [
            'item' => new PageModel(),
            'categories' => $categories,
        ]);
    }

    /**
     * Создание страницы.
     *
     * @param SavePageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SavePageRequest $request): RedirectResponse
    {
        return $this->save($request);
    }

    /**
     * Редактирование страницы.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null): View
    {
        if (! is_null($id) && $id > 0 && $item = PageModel::find($id)) {
            $categories = CategoryModel::getTree();

            return view('admin.module.pages::back.pages.form', [
                'item' => $item,
                'categories' => $categories,
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
    public function update(SavePageRequest $request, $id = null): RedirectResponse
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
    private function save($request, $id = null): RedirectResponse
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
        $this->saveImages($item, $request, ['og_image', 'preview', 'content'], 'pages');

        // Обновление поискового индекса.
        $item->searchable();

        event(new ModifyPageEvent($item));

        Session::flash('success', 'Страница «'.$item->title.'» успешно '.$action);

        return response()->redirectToRoute('back.pages.edit', [
            $item->fresh()->id,
        ]);
    }

    /**
     * Удаление страницы.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null): JsonResponse
    {
        if (! is_null($id) && $id > 0 && $item = PageModel::find($id)) {
            event(new ModifyPageEvent($item));

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
    public function getSlug(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $slug = SlugService::createSlug(PageModel::class, 'slug', $name);

        return response()->json($slug);
    }
}

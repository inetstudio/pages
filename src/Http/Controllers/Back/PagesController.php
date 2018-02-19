<?php

namespace InetStudio\Pages\Http\Controllers\Back;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use InetStudio\Pages\Models\PageModel;
use Illuminate\Support\Facades\Session;
use InetStudio\Categories\Models\CategoryModel;
use InetStudio\Pages\Contracts\Events\ModifyPageEventContract;
use InetStudio\Meta\Http\Controllers\Back\Traits\MetaManipulationsTrait;
use InetStudio\Tags\Http\Controllers\Back\Traits\TagsManipulationsTrait;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;
use InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesControllerContract;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\ImagesManipulationsTrait;
use InetStudio\Categories\Http\Controllers\Back\Traits\CategoriesManipulationsTrait;

/**
 * Class PagesController.
 */
class PagesController extends Controller implements PagesControllerContract
{
    use MetaManipulationsTrait;
    use TagsManipulationsTrait;
    use ImagesManipulationsTrait;
    use CategoriesManipulationsTrait;

    /**
     * Список страниц.
     *
     * @param PagesDataTableServiceContract $dataTableService
     *
     * @return View
     */
    public function index(PagesDataTableServiceContract $dataTableService): View
    {
        $table = $dataTableService->html();

        return view('admin.module.pages::back.pages.index', compact('table'));
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
     * @param SavePageRequestContract $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SavePageRequestContract $request): RedirectResponse
    {
        return $this->save($request);
    }

    /**
     * Редактирование страницы.
     *
     * @param null $id
     *
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
     * @param SavePageRequestContract $request
     * @param null $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SavePageRequestContract $request, $id = null): RedirectResponse
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение страницы.
     *
     * @param SavePageRequestContract $request
     * @param null $id
     *
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

        $images = (config('pages.images.conversions')) ? array_keys(config('pages.images.conversions')) : [];
        $this->saveImages($item, $request, $images, 'pages');

        // Обновление поискового индекса.
        $item->searchable();

        event(app()->makeWith(ModifyPageEventContract::class, ['item' => $item]));

        Session::flash('success', 'Страница «'.$item->title.'» успешно '.$action);

        return response()->redirectToRoute('back.pages.edit', [
            $item->fresh()->id,
        ]);
    }

    /**
     * Удаление страницы.
     *
     * @param null $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null): JsonResponse
    {
        if (! is_null($id) && $id > 0 && $item = PageModel::find($id)) {
            event(app()->makeWith(ModifyPageEventContract::class, ['item' => $item]));

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
}

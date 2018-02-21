<?php

namespace InetStudio\Pages\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Categories\Models\CategoryModel;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesControllerContract;
use InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract;
use InetStudio\Pages\Contracts\Http\Responses\Back\Pages\SaveResponseContract;
use InetStudio\Pages\Contracts\Http\Responses\Back\Pages\IndexResponseContract;
use InetStudio\Pages\Contracts\Http\Responses\Back\Pages\DestroyResponseContract;

/**
 * Class PagesController.
 */
class PagesController extends Controller implements PagesControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    private $services;

    /**
     * PagesController constructor.
     */
    public function __construct()
    {
        $this->services['pages'] = app()->make('InetStudio\Pages\Contracts\Services\Back\PagesServiceContract');
        $this->services['dataTables'] = app()->make('InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract');
    }

    /**
     * Список страниц.
     *
     * @return IndexResponseContract
     */
    public function index(): IndexResponseContract
    {
        $table = $this->services['dataTables']->html();

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\IndexResponseContract', [
            'data' => compact('table'),
        ]);
    }

    /**
     * Добавление страницы.
     *
     * @return FormResponseContract
     */
    public function create(): FormResponseContract
    {
        $item = $this->services['pages']->getPageObject();
        $categories = CategoryModel::getTree();

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract', [
            'data' => compact('item', 'categories'),
        ]);
    }

    /**
     * Создание страницы.
     *
     * @param SavePageRequestContract $request
     *
     * @return SaveResponseContract
     */
    public function store(SavePageRequestContract $request): SaveResponseContract
    {
        return $this->save($request);
    }

    /**
     * Редактирование страницы.
     *
     * @param int $id
     *
     * @return FormResponseContract
     */
    public function edit($id = 0): FormResponseContract
    {
        $item = $this->services['pages']->getPageObject($id);
        $categories = CategoryModel::getTree();

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract', [
            'data' => compact('item', 'categories'),
        ]);
    }

    /**
     * Обновление страницы.
     *
     * @param SavePageRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    public function update(SavePageRequestContract $request, int $id = 0): SaveResponseContract
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение страницы.
     *
     * @param SavePageRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    private function save(SavePageRequestContract $request, int $id = 0): SaveResponseContract
    {
        $item = $this->services['pages']->save($request, $id);

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\SaveResponseContract', [
            'page' => $item,
        ]);
    }

    /**
     * Удаление страницы.
     *
     * @param int $id
     *
     * @return DestroyResponseContract
     */
    public function destroy(int $id = 0): DestroyResponseContract
    {
        $item = $this->services['pages']->destroy($id);

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\DestroyResponseContract', [
            'page' => $item,
        ]);
    }
}

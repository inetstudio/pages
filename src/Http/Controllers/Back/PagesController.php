<?php

namespace InetStudio\Pages\Http\Controllers\Back;

use App\Http\Controllers\Controller;
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
        $this->services['categories'] = app()->make('InetStudio\Categories\Contracts\Services\Back\CategoriesServiceContract');
        $this->services['pages'] = app()->make('InetStudio\Pages\Contracts\Services\Back\PagesServiceContract');
        $this->services['dataTables'] = app()->make('InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract');
    }

    /**
     * Список объектов.
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
     * Добавление объекта.
     *
     * @return FormResponseContract
     */
    public function create(): FormResponseContract
    {
        $item = $this->services['pages']->getPageObject();
        $categories = $this->services['categories']->getTree();

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract', [
            'data' => compact('item', 'categories'),
        ]);
    }

    /**
     * Создание объекта.
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
     * Редактирование объекта.
     *
     * @param int $id
     *
     * @return FormResponseContract
     */
    public function edit($id = 0): FormResponseContract
    {
        $item = $this->services['pages']->getPageObject($id);
        $categories = $this->services['categories']->getTree();

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract', [
            'data' => compact('item', 'categories'),
        ]);
    }

    /**
     * Обновление объекта.
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
     * Сохранение объекта.
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
            'item' => $item,
        ]);
    }

    /**
     * Удаление объекта.
     *
     * @param int $id
     *
     * @return DestroyResponseContract
     */
    public function destroy(int $id = 0): DestroyResponseContract
    {
        $result = $this->services['pages']->destroy($id);

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\DestroyResponseContract', [
            'result' => ($result === null) ? false : $result,
        ]);
    }
}

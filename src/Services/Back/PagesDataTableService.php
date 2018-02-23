<?php

namespace InetStudio\Pages\Services\Back;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;
use InetStudio\Pages\Contracts\Repositories\Back\PagesRepositoryContract;
use InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract;

class PagesDataTableService extends DataTable implements PagesDataTableServiceContract
{
    /**
     * @var PagesRepositoryContract
     */
    private $repository;

    /**
     * PagesService constructor.
     *
     * @param PagesRepositoryContract $repository
     */
    public function __construct(PagesRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Запрос на получение данных таблицы.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        $transformer = app()->make('InetStudio\Pages\Contracts\Transformers\Back\PageTransformerContract');

        return DataTables::of($this->query())
            ->setTransformer($transformer)
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->repository->getAllItems(true);

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): Builder
    {
        $table = app('datatables.html');

        return $table
            ->columns($this->getColumns())
            ->ajax($this->getAjaxOptions())
            ->parameters($this->getParameters());
    }

    /**
     * Получаем колонки.
     *
     * @return array
     */
    protected function getColumns(): array
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
    protected function getAjaxOptions(): array
    {
        return [
            'url' => route('back.pages.data.index'),
            'type' => 'POST',
        ];
    }

    /**
     * Свойства datatables.
     *
     * @return array
     */
    protected function getParameters(): array
    {
        return [
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => [
                'url' => asset('/admin/js/plugins/datatables/locales/russian.json'),
            ],
        ];
    }
}

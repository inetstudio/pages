<?php

namespace InetStudio\Pages\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesDataControllerContract;

/**
 * Class PagesDataController.
 */
class PagesDataController extends Controller implements PagesDataControllerContract
{
    /**
     * Получаем данные для отображения в таблице.
     *
     * @param PagesDataTableServiceContract $dataTableService
     *
     * @return mixed
     */
    public function data(PagesDataTableServiceContract $dataTableService)
    {
        return $dataTableService->ajax();
    }
}

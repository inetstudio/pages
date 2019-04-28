<?php

namespace InetStudio\PagesPackage\Pages\Contracts\Services\Back;

use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract extends BaseServiceContract
{
    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return PageModelContract
     */
    public function save(array $data, int $id): PageModelContract;
}

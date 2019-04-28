<?php

namespace InetStudio\PagesPackage\Pages\Services\Front;

use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\AdminPanel\Base\Services\Traits\SlugsServiceTrait;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    use SlugsServiceTrait;

    /**
     * ItemsService constructor.
     *
     * @param  PageModelContract  $model
     */
    public function __construct(PageModelContract $model)
    {
        parent::__construct($model);
    }
}

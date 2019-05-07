<?php

namespace InetStudio\PagesPackage\Pages\Services\Back;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Base\Services\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\PagesPackage\Pages\Contracts\Services\Back\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * ItemsService constructor.
     *
     * @param  PageModelContract  $model
     */
    public function __construct(PageModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return PageModelContract
     *
     * @throws BindingResolutionException
     */
    public function save(array $data, int $id): PageModelContract
    {
        $action = ($id) ? 'отредактирована' : 'создана';

        $itemData = Arr::only($data, $this->model->getFillable());
        $item = $this->saveModel($itemData, $id);

        $metaData = Arr::get($data, 'meta', []);
        app()->make('InetStudio\MetaPackage\Meta\Contracts\Services\Back\ItemsServiceContract')
            ->attachToObject($metaData, $item);

        $images = (config('pages.images.conversions.page')) ? array_keys(config('pages.images.conversions.page')) : [];
        app()->make('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract')
            ->attachToObject(request(), $item, $images, 'pages', 'page');

        $item->searchable();

        event(
            app()->makeWith(
                'InetStudio\PagesPackage\Pages\Contracts\Events\Back\ModifyItemEventContract',
                compact('item')
            )
        );

        Session::flash('success', 'Страница «'.$item->title.'» успешно '.$action);

        return $item;
    }

    /**
     * Возвращаем статистику по страницам.
     *
     * @return mixed
     */
    public function getPagesStatistic()
    {
        return $this->model::count();
    }
}

<?php

namespace InetStudio\PagesPackage\Pages\Services\Front;

use League\Fractal\Manager;
use InetStudio\AdminPanel\Base\Services\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\SitemapServiceContract;

/**
 * Class SitemapService.
 */
class SitemapService extends BaseService implements SitemapServiceContract
{
    /**
     * SitemapService constructor.
     *
     * @param  PageModelContract  $model
     */
    public function __construct(PageModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Получаем информацию по объектам для карты сайта.
     *
     * @return array
     *
     * @throws BindingResolutionException
     */
    public function getItems(): array
    {
        $items = $this->model->buildQuery(
            [
                'columns' => ['created_at', 'updated_at'],
                'order' => ['created_at' => 'desc'],
            ]
        )->get();

        $transformer = app()->make(
            'InetStudio\PagesPackage\Pages\Contracts\Transformers\Front\Sitemap\ItemTransformerContract'
        );

        $resource = $transformer->transformCollection($items);

        $manager = new Manager();
        $serializer = app()->make(
            'InetStudio\AdminPanel\Base\Contracts\Serializers\SimpleDataArraySerializerContract'
        );
        $manager->setSerializer($serializer);

        $data = $manager->createData($resource)->toArray();

        return $data;
    }
}

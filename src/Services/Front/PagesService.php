<?php

namespace InetStudio\Pages\Services\Front;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Pages\Contracts\Services\Front\PagesServiceContract;

/**
 * Class PagesService.
 */
class PagesService implements PagesServiceContract
{
    /**
     * @var
     */
    public $repository;

    /**
     * PagesService constructor.
     */
    public function __construct()
    {
        $this->repository = app()->make('InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract');
    }

    /**
     * Получаем объект по slug.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getPageBySlug(string $slug, array $params = [])
    {
        return $this->repository->getItemBySlug($slug, $params);
    }

    /**
     * Получаем объекты по категории.
     *
     * @param string $categorySlug
     * @param array $params
     *
     * @return mixed
     */
    public function getPagesByCategory(string $categorySlug, array $params = [])
    {
        return $this->repository->getItemsByCategory($categorySlug, $params);
    }

    /**
     * Получаем информацию по объектам для карты сайта.
     *
     * @return array
     */
    public function getSiteMapItems(): array
    {
        $items = $this->repository->getAllItems([
            'columns' => ['created_at', 'updated_at'],
            'order' => ['created_at' => 'desc'],
        ]);

        $resource = app()->make('InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract')
            ->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}

<?php

namespace InetStudio\Pages\Services\Front;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Pages\Contracts\Services\Front\PagesServiceContract;
use InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract;

/**
 * Class PagesService.
 */
class PagesService implements PagesServiceContract
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
     * Получаем объект по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPageBySlug(string $slug, bool $returnBuilder = false)
    {
        return $this->repository->getItemBySlug($slug, $returnBuilder);
    }

    /**
     * Получаем объекты по категории.
     *
     * @param string $categorySlug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPagesByCategory(string $categorySlug, bool $returnBuilder = false)
    {
        return $this->repository->getItemsByCategory($categorySlug, $returnBuilder);
    }

    /**
     * Получаем информацию по объектам для карты сайта.
     *
     * @return array
     */
    public function getSiteMapItems(): array
    {
        $items = $this->repository->getAllItems();

        $resource = app()->make('InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract')
            ->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}

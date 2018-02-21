<?php

namespace InetStudio\Pages\Services\Front;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Pages\Contracts\Services\Front\PagesServiceContract;
use InetStudio\Pages\Contracts\Repositories\Back\PagesRepositoryContract;

/**
 * Class PagesService.
 */
class PagesService implements PagesServiceContract
{
    /**
     * @var PagesRepositoryContract
     */
    private $pagesRepository;

    /**
     * PagesService constructor.
     *
     * @param PagesRepositoryContract $pagesRepository
     */
    public function __construct(PagesRepositoryContract $pagesRepository)
    {
        $this->pagesRepository = $pagesRepository;
    }

    /**
     * Получаем страницу по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPageBySlug(string $slug, bool $returnBuilder = false)
    {
        return $this->pagesRepository->getPageBySlug($slug, $returnBuilder);
    }

    /**
     * Получаем страницы по категории.
     *
     * @param string $categorySlug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPagesByCategory(string $categorySlug, bool $returnBuilder = false)
    {
        return $this->pagesRepository->getPageBySlug($categorySlug, $returnBuilder);
    }

    /**
     * Получаем информацию по статьям для карты сайта.
     *
     * @return array
     */
    public function getSiteMapItems(): array
    {
        $pages = $this->pagesRepository->getAllPages();

        $resource = (app()->make('InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract'))->transformCollection($pages);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}

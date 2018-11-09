<?php

namespace InetStudio\Pages\Services\Front;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\AdminPanel\Services\Front\BaseService;
use InetStudio\AdminPanel\Services\Front\Traits\SlugsServiceTrait;
use InetStudio\Pages\Contracts\Services\Front\PagesServiceContract;

/**
 * Class PagesService.
 */
class PagesService extends BaseService implements PagesServiceContract
{
    use SlugsServiceTrait;

    /**
     * PagesService constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make('InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract'));
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

<?php

namespace InetStudio\Pages\Repositories;

use InetStudio\AdminPanel\Repositories\BaseRepository;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract;
use InetStudio\Categories\Repositories\Traits\CategoriesRepositoryTrait;

/**
 * Class PagesRepository.
 */
class PagesRepository extends BaseRepository implements PagesRepositoryContract
{
    use CategoriesRepositoryTrait;

    /**
     * PagesRepository constructor.
     *
     * @param PageModelContract $model
     */
    public function __construct(PageModelContract $model)
    {
        $this->model = $model;

        $this->defaultColumns = ['id', 'title', 'slug', 'created_at'];
        $this->relations = [
            'meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            },

            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            },

            'tags' => function ($query) {
                $query->select(['id', 'name', 'slug']);
            },
        ];
    }

    /**
     * Получаем объекты по slug.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getItemBySlug(string $slug, array $params = [])
    {
        $builder = $this->getItemsQuery($params)
            ->whereSlug($slug);

        $item = $builder->first();

        return $item;
    }
}

<?php

namespace InetStudio\Pages\Repositories;

use InetStudio\AdminPanel\Repositories\BaseRepository;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\AdminPanel\Repositories\Traits\SlugsRepositoryTrait;
use InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract;

/**
 * Class PagesRepository.
 */
class PagesRepository extends BaseRepository implements PagesRepositoryContract
{
    use SlugsRepositoryTrait;

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
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk', 'mime_type', 'custom_properties', 'responsive_images']);
            },
        ];
    }
}

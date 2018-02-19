<?php

namespace InetStudio\Pages\Services\Front;

use League\Fractal\Manager;
use InetStudio\Pages\Models\PageModel;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Pages\Contracts\Services\Front\PagesServiceContract;
use InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract;

/**
 * Class PagesService.
 */
class PagesService implements PagesServiceContract
{
    /**
     * Получаем страницу по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public static function getPageBySlug(string $slug, bool $returnBuilder = false)
    {
        $builder = PageModel::select(['id', 'title', 'content', 'slug'])
                ->with(['meta' => function ($query) {
                    $query->select(['metable_id', 'metable_type', 'key', 'value']);
                }, 'media' => function ($query) {
                    $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
                }])
                ->whereSlug($slug);

        if ($returnBuilder) {
            return $builder;
        }

        $page = $builder->first();

        if (! $page) {
            abort(404);
        }

        return $page;
    }

    /**
     * Получаем страницы по категории.
     *
     * @param $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public static function getPagesByCategory($slug, bool $returnBuilder = false)
    {
        $builder = PageModel::select(['id', 'title', 'description', 'slug'])
            ->with(['meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            }, 'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            }])
            ->withCategories($slug);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем информацию по статьям для карты сайта.
     *
     * @return array
     */
    public function getSiteMapItems(): array
    {
        $pages = PageModel::select(['slug', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc')
            ->get();

        $resource = (app()->make(PagesSiteMapTransformerContract::class))->transformCollection($pages);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}

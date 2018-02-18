<?php

namespace InetStudio\Pages\Services\Front;

use League\Fractal\Manager;
use InetStudio\Pages\Models\PageModel;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Pages\Contracts\Services\PagesServiceContract;
use InetStudio\Pages\Transformers\Front\PagesSiteMapTransformer;

/**
 * Class PagesService
 * @package InetStudio\Pages\Services\Front
 */
class PagesService implements PagesServiceContract
{
    /**
     * Получаем страницу по slug.
     *
     * @param string $slug
     *
     * @return PageModel
     */
    public function getPageBySlug(string $slug): PageModel
    {
        $cacheKey = 'PagesService_getPageBySlug_'.md5($slug);

        //return \Cache::tags(['pages'])->remember($cacheKey, 1440, function() use ($slug) {
        return \Cache::remember($cacheKey, 1440, function() use ($slug) {
            $items = PageModel::select(['id', 'title', 'content', 'slug'])
                ->with(['meta' => function ($query) {
                    $query->select(['metable_id', 'metable_type', 'key', 'value']);
                }, 'media' => function ($query) {
                    $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
                }])
                ->whereSlug($slug)
                ->get();

            if ($items->count() == 0) {
                abort(404);
            }

            return $items->first();
        });
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

        $resource = (new PagesSiteMapTransformer())->transformCollection($pages);

        return $this->serializeToArray($resource);
    }

    /**
     * Преобразовываем данные в массив.
     *
     * @param $resource
     *
     * @return array
     */
    private function serializeToArray($resource): array
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}

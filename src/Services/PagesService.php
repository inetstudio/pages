<?php

namespace InetStudio\Pages\Services;

use InetStudio\Pages\Models\PageModel;

class PagesService
{
    /**
     * Получаем страницу по slug.
     *
     * @param string $slug
     * @return PageModel
     */
    public function getPageBySlug(string $slug): PageModel
    {
        $cacheKey = 'PagesService_getPageBySlug_'.md5($slug);

        return \Cache::tags(['pages'])->remember($cacheKey, 1440, function() use ($slug) {
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
}

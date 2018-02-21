<?php

namespace InetStudio\Pages\Transformers\Front;

use League\Fractal\TransformerAbstract;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract;

/**
 * Class PagesSiteMapTransformer.
 */
class PagesSiteMapTransformer extends TransformerAbstract implements PagesSiteMapTransformerContract
{
    /**
     * Подготовка данных для отображения в карте сайта.
     *
     * @param PageModelContract $page
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(PageModelContract $page): array
    {
        return [
            'loc' => $page->href,
            'lastmod' => $page->updated_at->toW3cString(),
            'priority' => '0.6',
            'freq' => 'monthly',
        ];
    }

    /**
     * Обработка коллекции страниц.
     *
     * @param $pages
     *
     * @return FractalCollection
     */
    public function transformCollection($pages): FractalCollection
    {
        return new FractalCollection($pages, $this);
    }
}

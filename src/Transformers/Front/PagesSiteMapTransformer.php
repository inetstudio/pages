<?php

namespace InetStudio\Pages\Transformers\Front;

use InetStudio\Pages\Models\PageModel;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;

/**
 * Class PagesSiteMapTransformer
 * @package InetStudio\Pages\Transformers\Front
 */
class PagesSiteMapTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в карте сайта.
     *
     * @param PageModel $page
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(PageModel $page): array
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
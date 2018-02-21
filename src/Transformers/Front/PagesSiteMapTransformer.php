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
     * @param PageModelContract $item
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(PageModelContract $item): array
    {
        return [
            'loc' => $item->href,
            'lastmod' => $item->updated_at->toW3cString(),
            'priority' => '0.6',
            'freq' => 'monthly',
        ];
    }

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection
    {
        return new FractalCollection($items, $this);
    }
}

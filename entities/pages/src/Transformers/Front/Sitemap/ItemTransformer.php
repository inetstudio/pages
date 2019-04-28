<?php

namespace InetStudio\PagesPackage\Pages\Transformers\Front\Sitemap;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\PagesPackage\Pages\Contracts\Transformers\Front\Sitemap\ItemTransformerContract;

/**
 * Class ItemTransformer.
 */
class ItemTransformer extends TransformerAbstract implements ItemTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  PageModelContract  $item
     *
     * @return array
     */
    public function transform(PageModelContract $item): array
    {
        /** @var Carbon $updatedAt */
        $updatedAt = $item['updated_at'];

        return [
            'loc' => $item['href'],
            'lastmod' => $updatedAt->toW3cString(),
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

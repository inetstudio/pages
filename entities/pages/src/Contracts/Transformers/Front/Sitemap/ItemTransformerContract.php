<?php

namespace InetStudio\PagesPackage\Pages\Contracts\Transformers\Front\Sitemap;

use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;

/**
 * Interface ItemTransformerContract.
 */
interface ItemTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  PageModelContract  $item
     *
     * @return array
     */
    public function transform(PageModelContract $item): array;

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection;
}

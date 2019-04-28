<?php

namespace InetStudio\PagesPackage\Pages\Contracts\Transformers\Back\Resource;

use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;

/**
 * Interface IndexTransformerContract.
 */
interface IndexTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  PageModelContract  $item
     *
     * @return array
     */
    public function transform(PageModelContract $item): array;
}

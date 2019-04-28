<?php

namespace InetStudio\PagesPackage\Pages\Transformers\Back\Resource;

use Throwable;
use League\Fractal\TransformerAbstract;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\PagesPackage\Pages\Contracts\Transformers\Back\Resource\IndexTransformerContract;

/**
 * Class IndexTransformer.
 */
class IndexTransformer extends TransformerAbstract implements IndexTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  PageModelContract  $item
     *
     * @return array
     *
     * @throws Throwable
     */
    public function transform(PageModelContract $item): array
    {
        return [
            'id' => (int) $item['id'],
            'title' => $item['title'],
            'created_at' => (string) $item['created_at'],
            'updated_at' => (string) $item['updated_at'],
            'actions' => view(
                'admin.module.pages::back.partials.datatables.actions',
                [
                    'id' => $item['id'],
                    'href' => $item['href'],
                ]
            )->render(),
        ];
    }
}

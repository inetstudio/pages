<?php

namespace InetStudio\Pages\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Transformers\Back\PageTransformerContract;

class PageTransformer extends TransformerAbstract implements PageTransformerContract
{
    /**
     * Подготовка данных для отображения в таблице.
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
            'id' => (int) $item->id,
            'title' => $item->title,
            'created_at' => (string) $item->created_at,
            'updated_at' => (string) $item->updated_at,
            'actions' => view('admin.module.pages::back.partials.datatables.actions', [
                'id' => $item->id,
                'href' => $item->href,
            ])->render(),
        ];
    }
}

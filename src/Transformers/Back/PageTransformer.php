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
     * @param PageModelContract $page
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(PageModelContract $page): array
    {
        return [
            'id' => (int) $page->id,
            'title' => $page->title,
            'created_at' => (string) $page->created_at,
            'updated_at' => (string) $page->updated_at,
            'actions' => view('admin.module.pages::back.partials.datatables.actions', [
                'id' => $page->id,
                'href' => $page->href,
            ])->render(),
        ];
    }
}

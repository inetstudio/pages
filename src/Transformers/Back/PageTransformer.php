<?php

namespace Inetstudio\Pages\Transformers\Back;

use InetStudio\Pages\Models\PageModel;
use League\Fractal\TransformerAbstract;

class PageTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
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

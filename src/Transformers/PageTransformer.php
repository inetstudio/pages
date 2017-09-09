<?php

namespace Inetstudio\Pages\Transformers;

use InetStudio\Pages\Models\PageModel;
use League\Fractal\TransformerAbstract;

class PageTransformer extends TransformerAbstract
{
    /**
     * @param PageModel $page
     * @return array
     */
    public function transform(PageModel $page)
    {
        return [
            'id' => (int) $page->id,
            'title' => $page->title,
            'created_at' => (string) $page->created_at,
            'updated_at' => (string) $page->updated_at,
            'actions' => view('admin.module.pages::partials.datatables.actions', [
                'id' => $page->id,
                'href' => $page->href,
            ])->render(),
        ];
    }
}

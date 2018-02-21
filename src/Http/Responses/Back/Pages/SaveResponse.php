<?php

namespace InetStudio\Pages\Http\Responses\Back\Pages;

use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Http\Responses\Back\Pages\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract, Responsable
{
    /**
     * @var PageModelContract $page
     */
    private $page;

    /**
     * SaveResponse constructor.
     *
     * @param PageModelContract $page
     */
    public function __construct(PageModelContract $page)
    {
        $this->page = $page;
    }

    /**
     * Возвращаем ответ при сохранении страницы.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return response()->redirectToRoute('back.pages.edit', [
            $this->page->fresh()->id,
        ]);
    }
}

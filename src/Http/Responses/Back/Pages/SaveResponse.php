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
     * @var PageModelContract
     */
    private $item;

    /**
     * SaveResponse constructor.
     *
     * @param PageModelContract $item
     */
    public function __construct(PageModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при сохранении объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return response()->redirectToRoute('back.pages.edit', [
            $this->item->fresh()->id,
        ]);
    }
}

<?php

namespace InetStudio\Pages\Http\Responses\Back\Pages;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Http\Responses\Back\Pages\DestroyResponseContract;

/**
 * Class DestroyResponse.
 */
class DestroyResponse implements DestroyResponseContract, Responsable
{
    /**
     * @var PageModelContract
     */
    private $page;

    /**
     * DestroyResponse constructor.
     *
     * @param PageModelContract $page
     */
    public function __construct(PageModelContract $page)
    {
        $this->page = $page;
    }

    /**
     * Возвращаем ответ при удалении страницы.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => ($this->page->id) ? true : false,
        ]);
    }
}

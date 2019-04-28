<?php

namespace InetStudio\PagesPackage\Pages\Http\Responses\Back\Resource;

use Illuminate\Http\Request;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Resource\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract
{
    /**
     * @var PageModelContract
     */
    protected $item;

    /**
     * SaveResponse constructor.
     *
     * @param  PageModelContract  $item
     */
    public function __construct(PageModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при сохранении объекта.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $item = $this->item->fresh();

        return response()->redirectToRoute(
            'back.pages.edit',
            [
                $item['id'],
            ]
        );
    }
}

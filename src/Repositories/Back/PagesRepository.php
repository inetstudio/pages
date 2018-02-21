<?php

namespace InetStudio\Pages\Repositories\Back;

use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Repositories\Back\PagesRepositoryContract;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;

/**
 * Class PagesRepository.
 */
class PagesRepository implements PagesRepositoryContract
{
    /**
     * @var PageModelContract
     */
    private $page;

    /**
     * PagesRepository constructor.
     *
     * @param PageModelContract $page
     */
    public function __construct(PageModelContract $page)
    {
        $this->page = $page;
    }

    /**
     * Возвращаем страницу по id, либо создаем новую.
     *
     * @param int $id
     *
     * @return PageModelContract
     */
    public function getByID(int $id): PageModelContract
    {
        if (! (! is_null($id) && $id > 0 && $item = $this->page::find($id))) {
            $item = $this->page;
        }

        return $item;
    }

    /**
     * Сохраняем страницу.
     *
     * @param SavePageRequestContract $request
     * @param int $id
     *
     * @return PageModelContract
     */
    public function save(SavePageRequestContract $request, int $id): PageModelContract
    {
        $item = $this->getByID($id);

        $item->title = strip_tags($request->get('title'));
        $item->slug = strip_tags($request->get('slug'));
        $item->description = strip_tags($request->input('description.text'));
        $item->content = $request->input('content.text');
        $item->save();

        return $item;
    }

    public function destroy($id)
    {
        return $this->getByID($id)->delete();
    }
}

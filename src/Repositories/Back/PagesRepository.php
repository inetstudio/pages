<?php

namespace InetStudio\Pages\Repositories\Back;

use Illuminate\Support\Collection;
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

    /**
     * Удаляем страницу.
     *
     * @param int $id
     *
     * @return PageModelContract
     */
    public function destroy($id): PageModelContract
    {
        return $this->getByID($id)->delete();
    }

    /**
     * Ищем страницы.
     *
     * @param string $field
     * @param $value
     *
     * @return Collection
     */
    public function searchByField(string $field, string $value): Collection
    {
        return $this->page::select(['id', 'title', 'slug'])->where($field, 'LIKE', '%'.$value.'%')->get();
    }

    /**
     * Получаем все страницы.
     *
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getAllPages(bool $returnBuilder = false)
    {
        $builder = $this->page::select(['id', 'title', 'slug', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc');

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем страницу по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPageBySlug(string $slug, bool $returnBuilder = false)
    {
        $builder = $this->page::select(['id', 'title', 'content', 'slug'])
            ->with(['meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            }, 'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            }])
            ->whereSlug($slug);

        if ($returnBuilder) {
            return $builder;
        }

        $page = $builder->first();

        return $page;
    }

    /**
     * Получаем страницы по категории.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPagesByCategory(string $slug, bool $returnBuilder = false)
    {
        $builder = $this->page::select(['id', 'title', 'description', 'slug'])
            ->with(['meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            }, 'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            }])
            ->withCategories($slug);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }
}

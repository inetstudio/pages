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
    private $model;

    /**
     * PagesRepository constructor.
     *
     * @param PageModelContract $model
     */
    public function __construct(PageModelContract $model)
    {
        $this->model = $model;
    }

    /**
     * Возвращаем объект по id, либо создаем новый.
     *
     * @param int $id
     *
     * @return PageModelContract
     */
    public function getByID(int $id): PageModelContract
    {
        if (! (! is_null($id) && $id > 0 && $item = $this->model::find($id))) {
            $item = $this->model;
        }

        return $item;
    }

    /**
     * Сохраняем объект.
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
     * Удаляем объект.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy($id): ?bool
    {
        return $this->getByID($id)->delete();
    }

    /**
     * Ищем объекты.
     *
     * @param string $field
     * @param $value
     *
     * @return Collection
     */
    public function searchByField(string $field, string $value): Collection
    {
        return $this->model::select(['id', 'title', 'slug'])->where($field, 'LIKE', '%'.$value.'%')->get();
    }

    /**
     * Получаем все объекты.
     *
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getAllPages(bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'title', 'slug', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc');

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем объекты по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPageBySlug(string $slug, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'title', 'content', 'slug'])
            ->with(['meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            }, 'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            }])
            ->whereSlug($slug);

        if ($returnBuilder) {
            return $builder;
        }

        $item = $builder->first();

        return $item;
    }

    /**
     * Получаем объекты по категории.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPagesByCategory(string $slug, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'title', 'description', 'slug'])
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

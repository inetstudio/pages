<?php

namespace InetStudio\Pages\Repositories\Back;

use Illuminate\Support\Collection;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Repositories\Back\PagesRepositoryContract;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;
use InetStudio\Categories\Repositories\Back\Traits\CategoriesRepositoryTrait;

/**
 * Class PagesRepository.
 */
class PagesRepository implements PagesRepositoryContract
{
    use CategoriesRepositoryTrait;

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
    public function getItemByID(int $id): PageModelContract
    {
        if (! (! is_null($id) && $id > 0 && $item = $this->model::find($id))) {
            $item = $this->model;
        }

        return $item;
    }

    /**
     * Возвращаем объекты по списку id.
     *
     * @param $ids
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByIDs($ids, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'title', 'slug'])
            ->whereIn('id', (array) $ids);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
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
        $item = $this->getItemByID($id);

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
        return $this->getItemByID($id)->delete();
    }

    /**
     * Ищем объекты.
     *
     * @param string $field
     * @param $value
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function searchItemsByField(string $field, string $value, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'title', 'slug'])
            ->where($field, 'LIKE', '%'.$value.'%');

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем все объекты.
     *
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getAllItems(bool $returnBuilder = false)
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
    public function getItemBySlug(string $slug, bool $returnBuilder = false)
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
}

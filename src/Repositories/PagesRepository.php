<?php

namespace InetStudio\Pages\Repositories;

use Illuminate\Database\Eloquent\Builder;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract;
use InetStudio\Categories\Repositories\Traits\CategoriesRepositoryTrait;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;

/**
 * Class PagesRepository.
 */
class PagesRepository implements PagesRepositoryContract
{
    use CategoriesRepositoryTrait;

    /**
     * @var PageModelContract
     */
    public $model;

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
     * Получаем модель репозитория.
     *
     * @return PageModelContract
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Возвращаем пустой объект по id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getEmptyObjectById(int $id)
    {
        return $this->model::select(['id'])->where('id', '=', $id)->first();
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
        return $this->model::find($id) ?? new $this->model;
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
        $builder = $this->getItemsQuery()->whereIn('id', (array) $ids);

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
        $item->description = $request->input('description.text');
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
     * @param array $conditions
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function searchItems(array $conditions, bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery([])->where($conditions);

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
        $builder = $this->getItemsQuery(['updated_at'], [])->orderBy('created_at', 'desc');

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
        $builder = $this->getItemsQuery(['content'], ['meta', 'media'])->whereSlug($slug);

        if ($returnBuilder) {
            return $builder;
        }

        $item = $builder->first();

        return $item;
    }

    /**
     * Возвращаем запрос на получение объектов.
     *
     * @param array $extColumns
     * @param array $with
     *
     * @return Builder
     */
    protected function getItemsQuery($extColumns = [], $with = []): Builder
    {
        $defaultColumns = ['id', 'title', 'slug', 'created_at'];

        $relations = [
            'meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            },

            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            },

            'tags' => function ($query) {
                $query->select(['id', 'name', 'slug']);
            },
        ];

        return $this->model::select(array_merge($defaultColumns, $extColumns))
            ->with(array_intersect_key($relations, array_flip($with)));
    }
}

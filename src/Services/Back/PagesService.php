<?php

namespace InetStudio\Pages\Services\Back;

use League\Fractal\Manager;
use Illuminate\Support\Facades\Session;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Services\Back\PagesServiceContract;
use InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;

/**
 * Class PagesService.
 */
class PagesService implements PagesServiceContract
{
    /**
     * @var PagesRepositoryContract
     */
    private $repository;

    /**
     * PagesService constructor.
     *
     * @param PagesRepositoryContract $repository
     */
    public function __construct(PagesRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получаем объект модели.
     *
     * @param int $id
     *
     * @return PageModelContract
     */
    public function getPageObject(int $id = 0)
    {
        return $this->repository->getItemByID($id);
    }

    /**
     * Получаем объекты по списку id.
     *
     * @param array|int $ids
     * @param array $params
     *
     * @return mixed
     */
    public function getPagesByIDs($ids, array $params = [])
    {
        return $this->repository->getItemsByIDs($ids, $params);
    }

    /**
     * Сохраняем модель.
     *
     * @param SavePageRequestContract $request
     * @param int $id
     *
     * @return PageModelContract
     */
    public function save(SavePageRequestContract $request, int $id): PageModelContract
    {
        $action = ($id) ? 'отредактирована' : 'создана';
        $item = $this->repository->save($request->only($this->repository->getModel()->getFillable()), $id);

        app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')
            ->attachToObject($request, $item);

        app()->make('InetStudio\Categories\Contracts\Services\Back\CategoriesServiceContract')
            ->attachToObject($request, $item);

        app()->make('InetStudio\Tags\Contracts\Services\Back\TagsServiceContract')
            ->attachToObject($request, $item);

        $images = (config('pages.images.conversions.page')) ? array_keys(config('pages.images.conversions.page')) : [];
        app()->make('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract')
            ->attachToObject($request, $item, $images, 'pages', 'page');

        $item->searchable();

        event(app()->makeWith('InetStudio\Pages\Contracts\Events\Back\ModifyPageEventContract', [
            'object' => $item,
        ]));

        Session::flash('success', 'Страница «'.$item->title.'» успешно '.$action);

        return $item;
    }

    /**
     * Удаляем модель.
     *
     * @param $id
     *
     * @return bool
     */
    public function destroy(int $id): ?bool
    {
        return $this->repository->destroy($id);
    }

    /**
     * Получаем подсказки.
     *
     * @param string $search
     * @param $type
     *
     * @return array
     */
    public function getSuggestions(string $search, $type): array
    {
        $items = $this->repository->searchItems([['title', 'LIKE', '%'.$search.'%']]);

        $resource = (app()->makeWith('InetStudio\Pages\Contracts\Transformers\Back\SuggestionTransformerContract', [
            'type' => $type,
        ]))->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        if ($type && $type == 'autocomplete') {
            $data['suggestions'] = $transformation['data'];
        } else {
            $data['items'] = $transformation['data'];
        }

        return $data;
    }
}

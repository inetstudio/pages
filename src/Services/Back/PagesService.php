<?php

namespace InetStudio\Pages\Services\Back;

use League\Fractal\Manager;
use Illuminate\Contracts\Session\Session;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Services\Back\PagesServiceContract;
use InetStudio\Pages\Contracts\Repositories\Back\PagesRepositoryContract;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;

/**
 * Class PagesService.
 */
class PagesService implements PagesServiceContract
{
    /**
     * @var PagesRepositoryContract
     */
    private $pagesRepository;

    /**
     * PagesService constructor.
     *
     * @param PagesRepositoryContract $pagesRepository
     */
    public function __construct(PagesRepositoryContract $pagesRepository)
    {
        $this->pagesRepository = $pagesRepository;
    }

    /**
     * Получаем объект модели страницы.
     *
     * @param int $id
     *
     * @return PageModelContract
     */
    public function getPageObject(int $id = 0)
    {
        return $this->pagesRepository->getByID($id);
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
        $action = ($id) ? 'отредактирована' : 'создана';
        $item = $this->pagesRepository->save($request, $id);

        app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')
            ->attachToObject($request, $item);

        app()->make('InetStudio\Categories\Contracts\Services\Back\CategoriesServiceContract')
            ->attachToObject($request, $item);

        app()->make('InetStudio\Tags\Contracts\Services\Back\TagsServiceContract')
            ->attachToObject($request, $item);

        $images = (config('pages.images.conversions')) ? array_keys(config('pages.images.conversions')) : [];
        app()->make('InetStudio\AdminPanel\Contracts\Services\Back\Images\ImagesServiceContract')
            ->attachToObject($request, $item, $images, 'pages');

        $item->searchable();

        event(app()->makeWith('InetStudio\Pages\Contracts\Events\Back\ModifyPageEventContract', [
            'object' => $item,
        ]));

        Session::flash('success', 'Страница «'.$item->title.'» успешно '.$action);

        return $item;
    }

    /**
     * Удаляем страницу.
     *
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $item = $this->pagesRepository->destroy($id);

        event(app()->makeWith('InetStudio\Pages\Contracts\Events\Back\ModifyPageEventContract', [
            'object' => $item,
        ]));

        return $item;
    }

    /**
     * Получаем подсказки.
     *
     * @param string $search
     * @param $type
     *
     * @return mixed
     */
    public function getSuggestions(string $search, $type)
    {
        $items = $this->pagesRepository->searchByField('title', $search);

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
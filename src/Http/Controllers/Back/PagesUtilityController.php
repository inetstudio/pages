<?php

namespace InetStudio\Pages\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SlugResponseContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesUtilityControllerContract;
use InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

/**
 * Class PagesUtilityController.
 */
class PagesUtilityController extends Controller implements PagesUtilityControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    public $services;

    /**
     * PagesUtilityController constructor.
     */
    public function __construct()
    {
        $this->services['pages'] = app()->make('InetStudio\Pages\Contracts\Services\Back\PagesServiceContract');
    }

    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     *
     * @return SlugResponseContract
     */
    public function getSlug(Request $request): SlugResponseContract
    {
        $id = (int) $request->get('id');
        $name = $request->get('name');

        $model = $this->services['pages']->getPageObject($id);

        $slug = ($name) ? SlugService::createSlug($model, 'slug', $name) : '';

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SlugResponseContract', [
            'slug' => $slug,
        ]);
    }

    /**
     * Возвращаем объекты для поля.
     *
     * @param Request $request
     *
     * @return SuggestionsResponseContract
     */
    public function getSuggestions(Request $request): SuggestionsResponseContract
    {
        $search = $request->get('q');
        $type = $request->get('type');

        $data = app()->make('InetStudio\Pages\Contracts\Services\Back\PagesServiceContract')
            ->getSuggestions($search, $type);

        return app()->makeWith('InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', [
            'suggestions' => $data,
        ]);
    }
}

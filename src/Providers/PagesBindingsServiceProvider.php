<?php

namespace InetStudio\Pages\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class PagesBindingsServiceProvider.
 */
class PagesBindingsServiceProvider extends ServiceProvider
{
    /**
    * @var  bool
    */
    protected $defer = true;

    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\Pages\Contracts\Events\Back\ModifyPageEventContract' => 'InetStudio\Pages\Events\Back\ModifyPageEvent',
        'InetStudio\Pages\Contracts\Http\Controllers\Back\PagesControllerContract' => 'InetStudio\Pages\Http\Controllers\Back\PagesController',
        'InetStudio\Pages\Contracts\Http\Controllers\Back\PagesDataControllerContract' => 'InetStudio\Pages\Http\Controllers\Back\PagesDataController',
        'InetStudio\Pages\Contracts\Http\Controllers\Back\PagesUtilityControllerContract' => 'InetStudio\Pages\Http\Controllers\Back\PagesUtilityController',
        'InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract' => 'InetStudio\Pages\Http\Requests\Back\SavePageRequest',
        'InetStudio\Pages\Contracts\Http\Responses\Back\Pages\DestroyResponseContract' => 'InetStudio\Pages\Http\Responses\Back\Pages\DestroyResponse',
        'InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract' => 'InetStudio\Pages\Http\Responses\Back\Pages\FormResponse',
        'InetStudio\Pages\Contracts\Http\Responses\Back\Pages\IndexResponseContract' => 'InetStudio\Pages\Http\Responses\Back\Pages\IndexResponse',
        'InetStudio\Pages\Contracts\Http\Responses\Back\Pages\SaveResponseContract' => 'InetStudio\Pages\Http\Responses\Back\Pages\SaveResponse',
        'InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SlugResponseContract' => 'InetStudio\Pages\Http\Responses\Back\Utility\SlugResponse',
        'InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract' => 'InetStudio\Pages\Http\Responses\Back\Utility\SuggestionsResponse',
        'InetStudio\Pages\Contracts\Models\PageModelContract' => 'InetStudio\Pages\Models\PageModel',
        'InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract' => 'InetStudio\Pages\Repositories\PagesRepository',
        'InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract' => 'InetStudio\Pages\Services\Back\PagesDataTableService',
        'InetStudio\Pages\Contracts\Services\Back\PagesServiceContract' => 'InetStudio\Pages\Services\Back\PagesService',
        'InetStudio\Pages\Contracts\Services\Front\PagesServiceContract' => 'InetStudio\Pages\Services\Front\PagesService',
        'InetStudio\Pages\Contracts\Transformers\Back\PageTransformerContract' => 'InetStudio\Pages\Transformers\Back\PageTransformer',
        'InetStudio\Pages\Contracts\Transformers\Back\SuggestionTransformerContract' => 'InetStudio\Pages\Transformers\Back\SuggestionTransformer',
        'InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract' => 'InetStudio\Pages\Transformers\Front\PagesSiteMapTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}

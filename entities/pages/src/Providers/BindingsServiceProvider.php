<?php

namespace InetStudio\PagesPackage\Pages\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'InetStudio\PagesPackage\Pages\Contracts\Events\Back\ModifyItemEventContract' => 'InetStudio\PagesPackage\Pages\Events\Back\ModifyItemEvent',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Controllers\Back\ResourceControllerContract' => 'InetStudio\PagesPackage\Pages\Http\Controllers\Back\ResourceController',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Controllers\Back\DataControllerContract' => 'InetStudio\PagesPackage\Pages\Http\Controllers\Back\DataController',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Controllers\Back\UtilityControllerContract' => 'InetStudio\PagesPackage\Pages\Http\Controllers\Back\UtilityController',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Requests\Back\SaveItemRequestContract' => 'InetStudio\PagesPackage\Pages\Http\Requests\Back\SaveItemRequest',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Resource\DestroyResponseContract' => 'InetStudio\PagesPackage\Pages\Http\Responses\Back\Resource\DestroyResponse',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Resource\FormResponseContract' => 'InetStudio\PagesPackage\Pages\Http\Responses\Back\Resource\FormResponse',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Resource\IndexResponseContract' => 'InetStudio\PagesPackage\Pages\Http\Responses\Back\Resource\IndexResponse',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Resource\SaveResponseContract' => 'InetStudio\PagesPackage\Pages\Http\Responses\Back\Resource\SaveResponse',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Utility\SlugResponseContract' => 'InetStudio\PagesPackage\Pages\Http\Responses\Back\Utility\SlugResponse',
        'InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract' => 'InetStudio\PagesPackage\Pages\Http\Responses\Back\Utility\SuggestionsResponse',
        'InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract' => 'InetStudio\PagesPackage\Pages\Models\PageModel',
        'InetStudio\PagesPackage\Pages\Contracts\Services\Back\DataTableServiceContract' => 'InetStudio\PagesPackage\Pages\Services\Back\DataTableService',
        'InetStudio\PagesPackage\Pages\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\PagesPackage\Pages\Services\Back\ItemsService',
        'InetStudio\PagesPackage\Pages\Contracts\Services\Back\UtilityServiceContract' => 'InetStudio\PagesPackage\Pages\Services\Back\UtilityService',
        'InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract' => 'InetStudio\PagesPackage\Pages\Services\Front\ItemsService',
        'InetStudio\PagesPackage\Pages\Contracts\Services\Front\SitemapServiceContract' => 'InetStudio\PagesPackage\Pages\Services\Front\SitemapService',
        'InetStudio\PagesPackage\Pages\Contracts\Transformers\Back\Resource\IndexTransformerContract' => 'InetStudio\PagesPackage\Pages\Transformers\Back\Resource\IndexTransformer',
        'InetStudio\PagesPackage\Pages\Contracts\Transformers\Back\Utility\SuggestionTransformerContract' => 'InetStudio\PagesPackage\Pages\Transformers\Back\Utility\SuggestionTransformer',
        'InetStudio\PagesPackage\Pages\Contracts\Transformers\Front\Sitemap\ItemTransformerContract' => 'InetStudio\PagesPackage\Pages\Transformers\Front\Sitemap\ItemTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}

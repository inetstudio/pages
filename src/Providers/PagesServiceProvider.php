<?php

namespace InetStudio\Pages\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class PagesServiceProvider.
 */
class PagesServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerViewComposers();
        $this->registerObservers();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\Pages\Console\Commands\SetupCommand',
                'InetStudio\Pages\Console\Commands\CreateFoldersCommand',
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../config/pages.php' => config_path('pages.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreatePagesTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_pages_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_pages_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Регистрация путей.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.pages');
    }

    /**
     * Register Page's view composers.
     *
     * @return void
     */
    public function registerViewComposers(): void
    {
        view()->composer('admin.module.pages::back.partials.analytics.materials.statistic', function ($view) {
            $itemsCount = app()->make('InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract')
                ->getAllItems(true)->count();

            $view->with('count', $itemsCount);
        });
    }

    /**
     * Регистрация наблюдателей.
     *
     * @return void
     */
    public function registerObservers(): void
    {
        $this->app->make('InetStudio\Pages\Contracts\Models\PageModelContract')::observe($this->app->make('InetStudio\Pages\Contracts\Observers\PageObserverContract'));
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    public function registerBindings(): void
    {
        // Controllers
        $this->app->bind('InetStudio\Pages\Contracts\Http\Controllers\Back\PagesControllerContract', 'InetStudio\Pages\Http\Controllers\Back\PagesController');
        $this->app->bind('InetStudio\Pages\Contracts\Http\Controllers\Back\PagesDataControllerContract', 'InetStudio\Pages\Http\Controllers\Back\PagesDataController');
        $this->app->bind('InetStudio\Pages\Contracts\Http\Controllers\Back\PagesUtilityControllerContract', 'InetStudio\Pages\Http\Controllers\Back\PagesUtilityController');

        // Events
        $this->app->bind('InetStudio\Pages\Contracts\Events\Back\ModifyPageEventContract', 'InetStudio\Pages\Events\Back\ModifyPageEvent');

        // Models
        $this->app->bind('InetStudio\Pages\Contracts\Models\PageModelContract', 'InetStudio\Pages\Models\PageModel');

        // Observers
        $this->app->bind('InetStudio\Pages\Contracts\Observers\PageObserverContract', 'InetStudio\Pages\Observers\PageObserver');

        // Repositories
        $this->app->bind('InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract', 'InetStudio\Pages\Repositories\PagesRepository');

        // Requests
        $this->app->bind('InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract', 'InetStudio\Pages\Http\Requests\Back\SavePageRequest');

        // Responses
        $this->app->bind('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\DestroyResponseContract', 'InetStudio\Pages\Http\Responses\Back\Pages\DestroyResponse');
        $this->app->bind('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract', 'InetStudio\Pages\Http\Responses\Back\Pages\FormResponse');
        $this->app->bind('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\IndexResponseContract', 'InetStudio\Pages\Http\Responses\Back\Pages\IndexResponse');
        $this->app->bind('InetStudio\Pages\Contracts\Http\Responses\Back\Pages\SaveResponseContract', 'InetStudio\Pages\Http\Responses\Back\Pages\SaveResponse');
        $this->app->bind('InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SlugResponseContract', 'InetStudio\Pages\Http\Responses\Back\Utility\SlugResponse');
        $this->app->bind('InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', 'InetStudio\Pages\Http\Responses\Back\Utility\SuggestionsResponse');

        // Services
        $this->app->bind('InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract', 'InetStudio\Pages\Services\Back\PagesDataTableService');
        $this->app->bind('InetStudio\Pages\Contracts\Services\Back\PagesObserverServiceContract', 'InetStudio\Pages\Services\Back\PagesObserverService');
        $this->app->bind('InetStudio\Pages\Contracts\Services\Back\PagesServiceContract', 'InetStudio\Pages\Services\Back\PagesService');
        $this->app->bind('InetStudio\Pages\Contracts\Services\Front\PagesServiceContract', 'InetStudio\Pages\Services\Front\PagesService');

        // Transformers
        $this->app->bind('InetStudio\Pages\Contracts\Transformers\Back\PageTransformerContract', 'InetStudio\Pages\Transformers\Back\PageTransformer');
        $this->app->bind('InetStudio\Pages\Contracts\Transformers\Back\SuggestionTransformerContract', 'InetStudio\Pages\Transformers\Back\SuggestionTransformer');
        $this->app->bind('InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract', 'InetStudio\Pages\Transformers\Front\PagesSiteMapTransformer');
    }
}

<?php

namespace InetStudio\Pages\Providers;

use InetStudio\Pages\Models\PageModel;
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
            $pagesCount = PageModel::count();

            $view->with('count', $pagesCount);
        });
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
        $this->app->bind('InetStudio\Pages\Contracts\Events\ModifyPageEventContract', 'InetStudio\Pages\Events\ModifyPageEvent');

        // Requests
        $this->app->bind('InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract', 'InetStudio\Pages\Http\Requests\Back\SavePageRequest');

        // Services
        $this->app->bind('InetStudio\Pages\Contracts\Services\Front\PagesServiceContract', 'InetStudio\Pages\Services\Front\PagesService');
        $this->app->bind('InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract', 'InetStudio\Pages\Services\Back\PagesDataTableService');

        // Transformers
        $this->app->bind('InetStudio\Pages\Contracts\Transformers\Back\PageTransformerContract', 'InetStudio\Pages\Transformers\Back\PageTransformer');
        $this->app->bind('InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract', 'InetStudio\Pages\Transformers\Front\PagesSiteMapTransformer');
    }
}

<?php

namespace InetStudio\Pages\Providers;

use InetStudio\Pages\Models\PageModel;
use Illuminate\Support\ServiceProvider;
use InetStudio\Pages\Events\ModifyPageEvent;
use InetStudio\Pages\Services\Front\PagesService;
use InetStudio\Pages\Console\Commands\SetupCommand;
use InetStudio\Pages\Transformers\Back\PageTransformer;
use InetStudio\Pages\Http\Requests\Back\SavePageRequest;
use InetStudio\Pages\Services\Back\PagesDataTableService;
use InetStudio\Pages\Console\Commands\CreateFoldersCommand;
use InetStudio\Pages\Http\Controllers\Back\PagesController;
use InetStudio\Pages\Contracts\Events\ModifyPageEventContract;
use InetStudio\Pages\Http\Controllers\Back\PagesDataController;
use InetStudio\Pages\Transformers\Front\PagesSiteMapTransformer;
use InetStudio\Pages\Http\Controllers\Back\PagesUtilityController;
use InetStudio\Pages\Contracts\Services\Front\PagesServiceContract;
use InetStudio\Pages\Contracts\Transformers\Back\PageTransformerContract;
use InetStudio\Pages\Contracts\Http\Requests\Back\SavePageRequestContract;
use InetStudio\Pages\Contracts\Services\Back\PagesDataTableServiceContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesControllerContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesDataControllerContract;
use InetStudio\Pages\Contracts\Transformers\Front\PagesSiteMapTransformerContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesUtilityControllerContract;

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
                SetupCommand::class,
                CreateFoldersCommand::class,
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
        $this->app->bind(PagesControllerContract::class, PagesController::class);
        $this->app->bind(PagesDataControllerContract::class, PagesDataController::class);
        $this->app->bind(PagesUtilityControllerContract::class, PagesUtilityController::class);

        // Events
        $this->app->bind(ModifyPageEventContract::class, ModifyPageEvent::class);

        // Requests
        $this->app->bind(SavePageRequestContract::class, SavePageRequest::class);

        // Services
        $this->app->bind(PagesServiceContract::class, PagesService::class);
        $this->app->bind(PagesDataTableServiceContract::class, PagesDataTableService::class);

        // Transformers
        $this->app->bind(PageTransformerContract::class, PageTransformer::class);
        $this->app->bind(PagesSiteMapTransformerContract::class, PagesSiteMapTransformer::class);
    }
}

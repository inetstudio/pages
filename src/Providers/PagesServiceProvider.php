<?php

namespace InetStudio\Pages\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use InetStudio\Pages\Events\ModifyPageEvent;
use InetStudio\Pages\Services\Front\PagesService;
use InetStudio\Pages\Console\Commands\SetupCommand;
use InetStudio\Pages\Listeners\ClearPagesCacheListener;
use InetStudio\Pages\Console\Commands\CreateFoldersCommand;
use InetStudio\Pages\Contracts\Services\PagesServiceContract;

/**
 * Class PagesServiceProvider
 * @package InetStudio\Pages\Providers
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
        $this->registerEvents();
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
     * Регистрация событий.
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen(ModifyPageEvent::class, ClearPagesCacheListener::class);
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    public function registerBindings(): void
    {
        $this->app->bind(PagesServiceContract::class, PagesService::class);
    }
}

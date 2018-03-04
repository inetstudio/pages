<?php

namespace InetStudio\Pages\Observers;

use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Observers\PageObserverContract;

/**
 * Class PageObserver.
 */
class PageObserver implements PageObserverContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    protected $services;

    /**
     * PageObserver constructor.
     */
    public function __construct()
    {
        $this->services['pagesObserver'] = app()->make('InetStudio\Pages\Contracts\Services\Back\PagesObserverServiceContract');
    }

    /**
     * Событие "объект создается".
     *
     * @param PageModelContract $item
     */
    public function creating(PageModelContract $item): void
    {
        $this->services['pagesObserver']->creating($item);
    }

    /**
     * Событие "объект создан".
     *
     * @param PageModelContract $item
     */
    public function created(PageModelContract $item): void
    {
        $this->services['pagesObserver']->created($item);
    }

    /**
     * Событие "объект обновляется".
     *
     * @param PageModelContract $item
     */
    public function updating(PageModelContract $item): void
    {
        $this->services['pagesObserver']->updating($item);
    }

    /**
     * Событие "объект обновлен".
     *
     * @param PageModelContract $item
     */
    public function updated(PageModelContract $item): void
    {
        $this->services['pagesObserver']->updated($item);
    }

    /**
     * Событие "объект подписки удаляется".
     *
     * @param PageModelContract $item
     */
    public function deleting(PageModelContract $item): void
    {
        $this->services['pagesObserver']->deleting($item);
    }

    /**
     * Событие "объект удален".
     *
     * @param PageModelContract $item
     */
    public function deleted(PageModelContract $item): void
    {
        $this->services['pagesObserver']->deleted($item);
    }
}

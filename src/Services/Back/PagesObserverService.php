<?php

namespace InetStudio\Pages\Services\Back;

use InetStudio\Pages\Contracts\Models\PageModelContract;
use InetStudio\Pages\Contracts\Repositories\PagesRepositoryContract;
use InetStudio\Pages\Contracts\Services\Back\PagesObserverServiceContract;

/**
 * Class PagesObserverService.
 */
class PagesObserverService implements PagesObserverServiceContract
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
     * Событие "объект создается".
     *
     * @param PageModelContract $item
     */
    public function creating(PageModelContract $item): void
    {
    }

    /**
     * Событие "объект создан".
     *
     * @param PageModelContract $item
     */
    public function created(PageModelContract $item): void
    {
    }

    /**
     * Событие "объект обновляется".
     *
     * @param PageModelContract $item
     */
    public function updating(PageModelContract $item): void
    {
    }

    /**
     * Событие "объект обновлен".
     *
     * @param PageModelContract $item
     */
    public function updated(PageModelContract $item): void
    {
    }

    /**
     * Событие "объект подписки удаляется".
     *
     * @param PageModelContract $item
     */
    public function deleting(PageModelContract $item): void
    {
    }

    /**
     * Событие "объект удален".
     *
     * @param PageModelContract $item
     */
    public function deleted(PageModelContract $item): void
    {
    }
}

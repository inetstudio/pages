<?php

namespace InetStudio\Pages\Listeners;

use Illuminate\Support\Facades\Cache;

class ClearPagesCacheListener
{
    /**
     * ClearPagesCacheListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param $event
     */
    public function handle($event): void
    {
        $object = $event->object;

        Cache::tags(['pages'])->forget('PagesService_getPageBySlug_'.md5($object->slug));
    }
}

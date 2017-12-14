<?php

namespace InetStudio\Pages\Events;

use Illuminate\Queue\SerializesModels;

class ModifyPageEvent
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * ModifyArticleEvent constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}

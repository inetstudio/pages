<?php

namespace InetStudio\Pages\Events;

use Illuminate\Queue\SerializesModels;
use InetStudio\Pages\Contracts\Events\ModifyPageEventContract;

/**
 * Class ModifyPageEvent.
 */
class ModifyPageEvent implements ModifyPageEventContract
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * ModifyPageEvent constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}

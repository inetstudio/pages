<?php

namespace InetStudio\Pages\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Pages\Contracts\Events\Back\ModifyPageEventContract;

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

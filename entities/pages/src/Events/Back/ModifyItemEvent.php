<?php

namespace InetStudio\PagesPackage\Pages\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\PagesPackage\Pages\Contracts\Events\Back\ModifyItemEventContract;

/**
 * Class ModifyItemEvent.
 */
class ModifyItemEvent implements ModifyItemEventContract
{
    use SerializesModels;

    /**
     * @var PageModelContract
     */
    public $item;

    /**
     * ModifyItemEvent constructor.
     *
     * @param  PageModelContract  $item
     */
    public function __construct(PageModelContract $item)
    {
        $this->item = $item;
    }
}

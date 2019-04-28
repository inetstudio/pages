<?php

namespace InetStudio\PagesPackage\Pages\Contracts\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use InetStudio\Meta\Contracts\Models\Traits\MetableContract;
use InetStudio\AdminPanel\Base\Contracts\Models\BaseModelContract;

/**
 * Interface PageModelContract.
 */
interface PageModelContract extends BaseModelContract, Auditable, HasMedia, MetableContract
{
}

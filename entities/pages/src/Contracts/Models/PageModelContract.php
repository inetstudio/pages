<?php

namespace InetStudio\PagesPackage\Pages\Contracts\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use InetStudio\AdminPanel\Base\Contracts\Models\BaseModelContract;

/**
 * Interface PageModelContract.
 */
interface PageModelContract extends BaseModelContract, Auditable, HasMedia
{
}

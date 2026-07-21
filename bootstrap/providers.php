<?php

use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Admin\Providers\AdminServiceProvider;
use Modules\Billing\Providers\BillingServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Drive\Providers\DriveServiceProvider;
use Modules\Sharing\Providers\SharingServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    CoreServiceProvider::class,
    AuthServiceProvider::class,
    DriveServiceProvider::class,
    SharingServiceProvider::class,
    BillingServiceProvider::class,
    AdminServiceProvider::class,
];

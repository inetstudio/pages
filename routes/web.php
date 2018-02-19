<?php

use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesControllerContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesDataControllerContract;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesUtilityControllerContract;

Route::group([
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back'
], function () {
    Route::any('pages/data', PagesDataControllerContract::class.'@data')->name('back.pages.data.index');
    Route::post('pages/slug', PagesUtilityControllerContract::class.'@getSlug')->name('back.pages.getSlug');
    Route::post('pages/suggestions', PagesUtilityControllerContract::class.'@getSuggestions')->name('back.pages.getSuggestions');

    Route::resource('pages', PagesControllerContract::class, ['except' => [
        'show',
    ], 'as' => 'back']);
});

<?php

Route::group([
    'namespace' => 'InetStudio\Pages\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::any('pages/data', 'PagesDataControllerContract@data')->name('back.pages.data.index');
    Route::post('pages/slug', 'PagesUtilityControllerContract@getSlug')->name('back.pages.getSlug');
    Route::post('pages/suggestions', 'PagesUtilityControllerContract@getSuggestions')->name('back.pages.getSuggestions');

    Route::resource('pages', 'PagesControllerContract', ['except' => [
        'show',
    ], 'as' => 'back']);
});

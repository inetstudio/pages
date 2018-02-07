<?php

Route::group(['namespace' => 'InetStudio\Pages\Http\Controllers\Back'], function () {
    Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
        Route::group(['middleware' => 'back.auth'], function () {
            Route::post('pages/slug', 'PagesController@getSlug')->name('back.pages.getSlug');
            Route::any('pages/data', 'PagesController@data')->name('back.pages.data');
            Route::post('pages/suggestions', 'PagesController@getSuggestions')->name('back.pages.getSuggestions');
            Route::resource('pages', 'PagesController', ['except' => [
                'show',
            ], 'as' => 'back']);
        });
    });
});

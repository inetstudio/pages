<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'InetStudio\PagesPackage\Pages\Contracts\Http\Controllers\Back',
        'middleware' => ['web', 'back.auth'],
        'prefix' => 'back',
    ],
    function () {
        Route::any('pages/data', 'DataControllerContract@data')
            ->name('back.pages.data.index');

        Route::post('pages/slug', 'UtilityControllerContract@getSlug')
            ->name('back.pages.getSlug');

        Route::post('pages/suggestions', 'UtilityControllerContract@getSuggestions')
            ->name('back.pages.getSuggestions');

        Route::resource(
            'pages', 'ResourceControllerContract',
            [
                'except' => [
                    'show',
                ],
                'as' => 'back',
            ]
        );
    }
);

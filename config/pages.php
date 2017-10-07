<?php

return [

    /*
     * Настройки таблиц
     */

    'datatables' => [
        'ajax' => [
            'index' => [
                'url' => 'back.pages.data',
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
        ],
        'table' => [
            'index' => [
                'paging' => true,
                'pagingType' => 'full_numbers',
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'language' => [
                    'url' => '/admin/js/plugins/datatables/locales/russian.json',
                ],
            ],
        ],
        'columns' => [
            'index' => [
                ['data' => 'title', 'name' => 'title', 'title' => 'Заголовок'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
                ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
        ],
    ],

    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'og_image' => [
                'default' => [
                    [
                        'name' => 'og_image_default',
                        'size' => [
                            'width' => 968,
                            'height' => 475,
                        ],
                    ],
                ],
            ],
            'preview' => [
                '3_2' => [
                    [
                        'name' => 'preview_3_2',
                        'size' => [
                            'width' => 768,
                            'height' => 512,
                        ],
                    ],
                    [
                        'name' => 'preview_sidebar',
                        'size' => [
                            'width' => 384,
                            'height' => 256,
                        ],
                    ],
                ],
                '3_4' => [
                    [
                        'name' => 'preview_3_4',
                        'size' => [
                            'width' => 384,
                            'height' => 512,
                        ],
                    ],
                ],
            ],
            'content' => [
                'default' => [
                    [
                        'name' => 'content_admin',
                        'size' => [
                            'width' => 140,
                        ],
                    ],
                    [
                        'name' => 'content_front',
                        'quality' => 70,
                        'fit' => [
                            'width' => 768,
                            'height' => 512,
                        ],
                    ],
                ],
            ],
        ],
    ],
];

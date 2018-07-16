<?php

return [

    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'page' => [
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
        'crops' => [
            'page' => [
                'preview' => [
                    [
                        'title' => 'Размер 3х4',
                        'name' => '3_4',
                        'ratio' => '3/4',
                        'size' => [
                            'width' => 384,
                            'height' => 512,
                            'type' => 'min',
                            'description' => 'Минимальный размер области 3x4 — 384x512 пикселей'
                        ],
                    ],
                    [
                        'title' => 'Размер 3х2',
                        'name' => '3_2',
                        'ratio' => '3/2',
                        'size' => [
                            'width' => 768,
                            'height' => 512,
                            'type' => 'min',
                            'description' => 'Минимальный размер области 3x2 — 768x512 пикселей'
                        ],
                    ],
                ],
            ],
        ],
    ],
];

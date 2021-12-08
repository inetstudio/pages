<?php

return [

    'media' => [
        'content' => [
            'collections' => [
                'content' => [
                    'conversions' => [
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
                    'customProperties' => [
                        [
                            'title' => 'Описание',
                            'name' => 'description',
                        ],
                        [
                            'title' => 'Copyright',
                            'name' => 'copyright',
                        ],
                        [
                            'title' => 'Alt',
                            'name' => 'alt',
                        ]
                    ],
                    'disks' => [
                        'disk' => 'pages',
                        'conversions_disk' => 'pages',
                    ],
                    'editor' => [],
                    'quality' => 75,
                    'title' => 'Изображения',
                    'uploaderOptions' => [
                        'acceptedFileTypes' => ['image/*'],
                        'allowMultiple' => true
                    ],
                ],
                'files' => [
                    'conversions' => [],
                    'customProperties' => [
                        [
                            'title' => 'Описание',
                            'name' => 'description',
                        ],
                        [
                            'title' => 'Copyright',
                            'name' => 'copyright',
                        ],
                        [
                            'title' => 'Alt',
                            'name' => 'alt',
                        ]
                    ],
                    'disks' => [
                        'disk' => 'pages',
                        'conversions_disk' => 'pages',
                    ],
                    'editor' => [],
                    'quality' => 75,
                    'title' => 'Файлы',
                    'uploaderOptions' => [
                        'allowMultiple' => true
                    ],
                ],
            ],
        ],
        'og_image' => [
            'collections' => [
                'og_image' => [
                    'conversions' => [
                        [
                            'name' => 'og_image_default',
                            'size' => [
                                'width' => 968,
                                'height' => 475,
                            ],
                        ],
                    ],
                    'disks' => [
                        'disk' => 'pages',
                        'conversions_disk' => 'pages',
                    ],
                    'editor' => [
                        'crops' => [
                            [
                                'name' => 'og_image_default',
                                'size' => [
                                    'width' => 968,
                                    'height' => 475,
                                    'type' => 'min',
                                    'description' => 'Минимальный размер области — 968x475 пикселей',
                                ],
                                'title' => 'Выбрать область',
                            ]
                        ],
                    ],
                    'quality' => 75,
                    'uploaderOptions' => [
                        'acceptedFileTypes' => ['image/*'],
                    ],
                ],
            ],
        ],
        'preview' => [
            'collections' => [
                'preview' => [
                    'conversions' => [
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
                        [
                            'name' => 'preview_3_4',
                            'size' => [
                                'width' => 384,
                                'height' => 512,
                            ],
                        ],
                    ],
                    'customProperties' => [
                        [
                            'title' => 'Описание',
                            'name' => 'description',
                        ],
                        [
                            'title' => 'Copyright',
                            'name' => 'copyright',
                        ],
                        [
                            'title' => 'Alt',
                            'name' => 'alt',
                        ]
                    ],
                    'disks' => [
                        'disk' => 'pages',
                        'conversions_disk' => 'pages',
                    ],
                    'editor' => [
                        'crops' => [
                            [
                                'name' => 'preview_3_4',
                                'size' => [
                                    'width' => 384,
                                    'height' => 512,
                                    'type' => 'min',
                                    'description' => 'Минимальный размер области 3x4 — 384x512 пикселей',
                                ],
                                'title' => 'Размер 3х4',
                            ],
                            [
                                'name' => 'preview_3_2',
                                'size' => [
                                    'width' => 768,
                                    'height' => 512,
                                    'type' => 'min',
                                    'description' => 'Минимальный размер области 3x2 — 768x512 пикселей',
                                ],
                                'title' => 'Размер 3х2',
                            ],
                        ],
                    ],
                    'quality' => 75,
                    'uploaderOptions' => [
                        'acceptedFileTypes' => ['image/*'],
                    ],
                ],
                'test' => [
                    'conversions' => [
                        [
                            'name' => 'preview_1_1',
                            'size' => [
                                'width' => 512,
                                'height' => 512,
                            ],
                        ],
                        [
                            'name' => 'preview_2_3',
                            'size' => [
                                'width' => 400,
                                'height' => 600,
                            ],
                        ],
                    ],
                    'customProperties' => [
                        [
                            'title' => 'Тест 1',
                            'name' => 'test_1',
                        ],
                        [
                            'title' => 'Тест 2',
                            'name' => 'test_2',
                        ],
                        [
                            'title' => 'Тест 3',
                            'name' => 'test_3',
                        ]
                    ],
                    'disks' => [
                        'disk' => 'pages',
                        'conversions_disk' => 'pages',
                    ],
                    'editor' => [
                        'crops' => [
                            [
                                'name' => 'preview_1_1',
                                'size' => [
                                    'width' => 512,
                                    'height' => 512,
                                    'type' => 'min',
                                    'description' => 'Минимальный размер области 1х1 — 512x512 пикселей',
                                ],
                                'title' => 'Размер 1х1',
                            ],
                            [
                                'name' => 'preview_2_3',
                                'size' => [
                                    'width' => 400,
                                    'height' => 600,
                                    'type' => 'min',
                                    'description' => 'Минимальный размер области 2x3 — 400x600 пикселей',
                                ],
                                'title' => 'Размер 2х3',
                            ],
                        ],
                    ],
                    'quality' => 75,
                    'title' => 'Второстепенное превью',
                    'uploaderOptions' => [
                        'allowMultiple' => true
                    ],
                ],
            ],
        ],
    ],
];

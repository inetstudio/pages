<?php

return [

    /*
     * Расширение файла конфигурации app/config/filesystems.php
     * добавляет локальные диски для хранения изображений страниц
     */

    'pages' => [
        'driver' => 'local',
        'root' => storage_path('app/public/pages'),
        'url' => env('APP_URL').'/storage/pages',
        'visibility' => 'public',
    ],

];

<?php

return [
    'build_path' => 'build',
    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL', 'http://localhost:5173'),
        'enabled' => env('VITE_DEV_SERVER_ENABLED', false),
    ],
];

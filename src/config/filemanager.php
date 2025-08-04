<?php
return [
    'disk' => env('FILE_MANAGER_DISK', 'sftp'),#local|public|sftp
    'default_path' => 'uploads',

    'model_map' => [
        'user' => \App\Models\User::class,
        // ...
    ],
];

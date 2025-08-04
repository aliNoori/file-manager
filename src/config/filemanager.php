<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Default Storage Disk
    |--------------------------------------------------------------------------
    | Supported options: 'local', 'public', 'sftp'
    */
    'disk' => env('FILE_MANAGER_DISK', 'sftp'),

    /*
    |--------------------------------------------------------------------------
    | Default Upload Path
    |--------------------------------------------------------------------------
    */
    'default_path' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | Model Map
    |--------------------------------------------------------------------------
    | Maps model aliases to actual class names.
    | Used for polymorphic file attachment.
    */
    'model_map' => [
        'user' => \App\Models\User::class,
        // Add more models as needed
    ],
];

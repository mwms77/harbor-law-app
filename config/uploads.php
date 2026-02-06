<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Maximum Upload File Size
    |--------------------------------------------------------------------------
    |
    | Maximum file size in kilobytes. Default is 10MB (10240 KB).
    | This should match or be lower than PHP's upload_max_filesize.
    |
    */

    'max_size' => env('UPLOAD_MAX_SIZE', 10240),

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of allowed file extensions for uploads.
    | These are validated on the server side.
    |
    */

    'allowed_mimes' => env('UPLOAD_ALLOWED_MIMES', 'pdf,jpg,jpeg,png,heic'),

    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | The disk where uploaded files will be stored.
    | Default is 'local' which stores in storage/app.
    |
    */

    'disk' => env('UPLOAD_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Upload Path
    |--------------------------------------------------------------------------
    |
    | Base path where client uploads are stored (within the storage disk).
    |
    */

    'path' => 'private/client-uploads',

];

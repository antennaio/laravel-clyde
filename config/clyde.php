<?php

return [

     /*
     |--------------------------------------------------------------------------
     | Source Filesystem
     |--------------------------------------------------------------------------
     |
     | Please specify source filesystem (local or s3).
     |
     */
    'source' => 'local',

     /*
     |--------------------------------------------------------------------------
     | Cache Filesystem
     |--------------------------------------------------------------------------
     |
     | Please specify cache filesystem (local or s3).
     |
     */
    'cache' => 'local',

     /*
     |--------------------------------------------------------------------------
     | Source Path Prefix
     |--------------------------------------------------------------------------
     |
     | This is a path where source files will be stored.
     |
     */
    'source_path_prefix' => '/uploads',

     /*
     |--------------------------------------------------------------------------
     | Cache Path Prefix
     |--------------------------------------------------------------------------
     |
     | This is a path where cache files will be stored.
     |
     */
    'cache_path_prefix' => '/uploads/.cache',

     /*
     |--------------------------------------------------------------------------
     | Max Image Size
     |--------------------------------------------------------------------------
     |
     | Set a reasonable max image size, so that manipulated images don't
     | go over a certain limit.
     |
     */
    'max_image_size' => 2000*2000,

     /*
     |--------------------------------------------------------------------------
     | Secure Urls
     |--------------------------------------------------------------------------
     |
     | If set to true, signed URLs will be generated as a security measure.
     |
     */
    'secure_urls' => true,

     /*
     |--------------------------------------------------------------------------
     | Sign Key
     |--------------------------------------------------------------------------
     |
     | A 128 character (or longer) signing key is recommended.
     |
     */
    'sign_key' => config('app.key'),

     /*
     |--------------------------------------------------------------------------
     | Url Prefix
     |--------------------------------------------------------------------------
     |
     | This is a string that will be visible in all generated URLs. Example:
     | 
     | /imgcache/56a1472beca5d.jpg
     |
     */
    'url_prefix' => 'imgcache',

     /*
     |--------------------------------------------------------------------------
     | Route Name
     |--------------------------------------------------------------------------
     |
     | The route name.
     |
     */
    'route_name' => 'laravel-clyde',

     /*
     |--------------------------------------------------------------------------
     | Presets
     |--------------------------------------------------------------------------
     |
     | You can setup various presets to simplify manipulation of images.
     |
     | For a full documentation of Image API see:
     | http://glide.thephpleague.com/1.0/api/quick-reference/
     |
     */
    'presets' => [
        'small' => [
            'w' => 400,
            'h' => 300,
            'fit' => 'crop'
        ],
        'medium' => [
            'w' => 800,
            'h' => 600,
            'fit' => 'crop'
        ],
        'large' => [
            'w' => 1600,
            'h' => 1200,
            'fit' => 'crop'
        ]
    ]

];

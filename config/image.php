<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    'default-size' => 'medium',

    'index-image-sizes' => [
        'small' => [
            'width' => 640,
            'height' => 640,
        ],

        'medium' => [
            'width' => 1280,
            'height' => 720,
        ],

        'large' => [
            'width' => 1920,
            'height' => 1280,
        ],
    ],
];

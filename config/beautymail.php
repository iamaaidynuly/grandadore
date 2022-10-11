<?php

return [

    // These CSS rules will be applied after the regular template CSS


    'css' => [
//        '.button-content .button { background: red }',
        '.imgpop { width: 80%; display:block; margin: 0 auto; }',
        '.imgpop img { display: block; max-width: 100%; }',
    ],


    'colors' => [

        'highlight' => '#333333',
        'button'    => '#333333',

    ],

    'view' => [
        'senderName'  => null,
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,

        'logo'        => [
            'path'   => '%PUBLIC%/images/logo-white.svg',
            'width'  => '',
            'height' => '',
        ],

        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];

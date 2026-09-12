<?php

return [

    'default' => 'main',

    'connections' => [

        'main' => [
            'salt' => env('HASHIDS_SALT', 'baseapp-hashids-salt-2026'),
            'length' => env('HASHIDS_LENGTH', 8),
        ],

    ],

];

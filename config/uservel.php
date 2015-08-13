<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    'users'       => [
        'avatar' => [
            // DB column name
            'column'  => 'avatar',
            // Default avatar file
            'default' => 'assets/img/misc/noavatar.png',
            // Path to the folder with uploaded avatars
            'path'    => 'uploads/images/avatars'
        ],
        'model'  => Atorscho\User::class,
        'table'  => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
    */

    'groups'      => [
        'default' => 1,
        'perPage' => 10,
        'rules'   => [
            'name'        => [
                'min' => 3,
                'max' => 30
            ],
            'handle'      => [
                'min' => 3,
                'max' => 30
            ],
            'description' => [
                'min' => 0,
                'max' => 255
            ],
            'prefix'      => [
                'min' => 0,
                'max' => 255
            ],
            'suffix'      => [
                'min' => 0,
                'max' => 255
            ]
        ]
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        'perPage' => 10,
        'rules'   => [
            'name'   => [
                'min' => 3,
                'max' => 30
            ],
            'handle' => [
                'min' => 3,
                'max' => 30
            ]
        ]
    ]

];

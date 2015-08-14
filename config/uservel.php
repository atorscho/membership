<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    'users'       => [
        'avatar'  => [
            // DB column name
            'column'  => 'avatar',
            // Default avatar file
            'default' => 'assets/img/misc/noavatar.png',
            // Path to the folder with uploaded avatars
            'path'    => 'uploads/images/avatars'
        ],
        'model'   => Atorscho\User::class,
        'perPage' => 10,
        'rules'   => [
            'username' => [
                'required' => true,
                'min'      => 3,
                'max'      => 30
            ],
            'email'    => [
                'required' => true,
                'min'      => 3,
                'max'      => 30
            ],
            'password' => [
                'required' => true,
                'min'      => 4,
                'max'      => 30
            ],
            'avatar'   => [
                'required' => false
            ],
        ],
        'table'   => 'users'
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
                'required' => true,
                'min'      => 3,
                'max'      => 30
            ],
            'handle'      => [
                'required' => false,
                'min'      => 3,
                'max'      => 30
            ],
            'description' => [
                'required' => false,
                'min'      => 0,
                'max'      => 255
            ],
            'prefix'      => [
                'required' => false,
                'min'      => 0,
                'max'      => 255
            ],
            'suffix'      => [
                'required' => false,
                'min'      => 0,
                'max'      => 255
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
                'required' => true,
                'min'      => 3,
                'max'      => 30
            ],
            'handle' => [
                'required' => false,
                'min'      => 3,
                'max'      => 30
            ]
        ]
    ]

];

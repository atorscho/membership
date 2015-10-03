<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */



    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    'users' => [
        'avatar'   => [
            // DB column name
            'column'  => 'avatar',
            // Default avatar file
            'default' => 'assets/img/misc/noavatar.png',
            // Path to the folder with uploaded avatars
            'path'    => 'uploads/images/avatars'
        ],
        'model'    => App\User::class,
        'per_page' => 10,
        'table'    => 'users'
    ],

    /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
    */

    'groups' => [
        'default'          => 1,
        'handle_separator' => '-', // except a comma "," and a pipe "|"
        'per_page'         => 10
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        'handle_separator' => '.', // except a comma "," and a pipe "|"
        'per_page'         => 10
    ],

];

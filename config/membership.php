<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    'users' => [
        'avatar'   => [
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
    |
    | Default group ID, separator for generated group handles, and number of
    | groups to show per page.
    |
    */

    'groups' => [
        'default'          => 1,
        'handle_separator' => '-', // comma "," and pipe "|" are reserved!
        'per_page'         => 10
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Separator for generated permission handles, and number of permissions
    | to show per page.
    |
    */

    'permissions' => [
        'handle_separator' => '.', // comma "," and pipe "|" are reserved!
        'per_page'         => 10
    ],

];

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
        // User Model class name
        'model'    => App\User::class,
        // Number of users to display per page
        'per_page' => 10,
        // Database table name
        'table'    => 'users'
    ],

    /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
    */

    'groups' => [
        // Default group ID
        'default'          => 1,
        // Used for auto-generated handles
        'handle_separator' => '-', // comma "," and pipe "|" are reserved!
        // Number of groups to display per page
        'per_page'         => 10
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        // Used for auto-generated handles
        'handle_separator' => '.', // comma "," and pipe "|" are reserved!
        // Number of permissions to display per page
        'per_page'         => 10
    ],

];

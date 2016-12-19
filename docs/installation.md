# Installation

## Composer

In order to install "Membership", you need to require it with composer:

```json
composer require "atorscho/membership"
```

## Service Provider

Now add new Service Provider to the `providers` array in `/config/app.php`:

```php
Atorscho\Membership\MembershipServiceProvider::class,
```

## User Model

You need also to update your `User` model. Simply use the `UserMembership` trait.

```php
<?php

namespace App;

use Atorscho\Membership\Users\UserMembership;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use UserMembership;

    // ...
}
```

## Setup

The final step is to run new artisan command:

```bash
$ php artisan membership:install
```

The installer has 4 steps:  
1. Publishing package files;  
2. Migrating the database;  
3. Populating the database with default data;  
4. Creating a super-user with all permissions.

## Laravel Gate

You may also register package's permissions via Laravel Gate by adding `Membership::registerPermissions()` to the method:

```php
// file: app/Providers/AuthServiceProvider.php

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        \Membership::registerPermissions($gate);
    }
}
```

This way you will be able to use `@can` Blade directive.


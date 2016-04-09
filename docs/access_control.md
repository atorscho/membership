# Access Control

There are various ways to restrict pages and content to specific users.

## Blade Directives

You may use the Blade directive `@can` that is available since Laravel 5.1.11 to check for a user permission.

To enable this feature, do not forget to call `\Membership::registerPermissions($gate)` as described [here][link-laravel-gate].

``` html
<ul>
	<li>Home</li>
	<li>Products</li>
	<li>Services</li>
	<li>About</li>
	@can('access.acp')
		<li>Admin</li>
	@endcan
</ul>
```

The directive `@is` is useful when you need to check for a user group belonging.

``` html
@is('banned')
	<div class="ui negative message">
		You cannot access the forums.
	</div>
@else
	<div class="ui negative message">
		Welcome to our forums!
	</div>
@endis
```


## Model

Another way is to use `User` model methods:

- `User::can()`
- `User::is()`

You can learn more about them [here][link-api].


## Middlewares

Sometimes you need to restrict access to a specific page. You can do so with two middlewares:

- `can:permission_handle`
- `is:group_handle`

``` php
class UsersController extends AdminController
{
	public function __construct()
	{
		$this->middleware('access.acp');
		$this->middleware('can:create.users', ['only' => ['create', 'store']]);
		$this->middleware('can:delete.users', ['only' => 'destroy']);
	}
}
```

[link-laravel-gate]: /docs/installation.md#laravel-gate
[link-api]: /docs/user-membership/api/classes-and-methods
# Classes & Methods

## Atorscho\Membership\Users\UserMembership

This trait must be used in the `User` model. (By default `App\User`)

``` php
public function groupPermissions(): Collection;
```

This method returns permissions that a user inherited from a group.

> **Note:** The result is cached forever since permissions do not need to be changed often, so a `php artisan cache:clear` may be needed.

---

``` php
public function allPermissions(): Collection;
```

This returns all user's permissions: own permissions and those inherited from groups.

> **Note:** The result is cached forever since permissions do not need to be changed often, so a > `php artisan cache:clear` may be needed.

---

``` php
public function is(array|string $groups, bool $strict = true): bool;
```

`$user->is('admin')` or `$user->is('admins')` will return true if the user belongs to the `admins` group.

You may specify an array of group handles as a first parameter, or a comma or pipe separated list of handles.

If you specify a comma separated list of handles, the function will perform a strict search which means it will return `true` **only** if the user belongs to **all** specified groups.

And contrary, if the first argument is a pipe separated list of group handles, the method will return true if the user belongs to **at least one** of the specified groups.

If an array of group handles is provided, you may change the second argument as needed.

> **Note:** Group handles may be in singular form too. 

---

``` php
public function can(array|string $permissions, Model $model = null, string $column = 'user_id'): bool;
```

The first argument behaves the same way as in `is()` method. It accepts an array, a comma or a pipe separated list of permission handles.

`$user->can('update.posts')` will return `true` only if the user has permission to edit posts, this permission is usually assigned to moderators so that they can modify other users' content.

`$user->can('update.posts', $post)` will return `true` if the user can edit posts or if the user is owner of the post. This is determined by `user_id` column in that database table. You may specify a custom column name as a third parameter.

---

``` php
public function owns(Model $model, string $column = 'user_id'): bool;
```

The function checks whether the user owns a model. This is determined by `user_id` column in the database table. You may change the column name in third argument.

*e.g.* `$user->owns($article) -> true|false`

---

``` php
public function avatar(): string;
```

Returns an absolute URI to the user's avatar. If none uploaded, use the default one.

*e.g.* `<img src="{{ $user->avatar() }}" />`

---

``` php
public function only(string $group);
```

A query scope.

Filter users that belong to the specified group (searched by its handle).

---

``` php
public function assignTo(Group|array|string $groups);
```

Assign the user to the specified group(s).

The parameter may be an instance of the `Atorscho\Membership\Groups\Group` class, a group handle (plural only) or an array of group objects or handles.

---

``` php
public function removeFrom(Group|array|string $groups);
```

Remove the user from specified group(s).

The parameter may be an instance of the `Atorscho\Membership\Groups\Group` class, a group handle (plural only) or an array of group objects or handles.

---

``` php
public function syncGroups(array $ids, bool $detaching = true);
```

Synchronize groups by attaching and detaching them.

---

``` php
public function givePermissionTo(Permission|array|string $permissions = null);
```

Grant the user new permission(s).

The parameter may be an instance of the `Atorscho\Membership\Permissions\Permission` class, a permission handle (plural only) or an array of permission objects or handles.

---

``` php
public function removePermissionTo(Permission|array|string $permissions = null);
```

Take specified permission(s) from the user.

The parameter may be an instance of the `Atorscho\Membership\Permissions\Permission` class, a permission handle (plural only) or an array of permission objects or handles.

---

``` php
public function syncPermissions(array $ids, bool $detaching = true);
```

Synchronize permissions by attaching and detaching them.


## Atorscho\Membership\Groups\Group

``` php
public function givePermissionTo(Permission|array|string $permissions = null);
```

*See description above.*

---

``` php
public function removePermissionTo(Permission|array|string $permissions = null);
```

*See description above.*

---

``` php
public function syncPermissions(array $ids, bool $detaching = true);
```

*See description above.*


## Atorscho\Membership\Permissions\Permission

``` php
public function assignTo(Group|array|string $groups);
```

*See description above.*

---

``` php
public function removeFrom(Group|array|string $groups);
```

*See description above.*

---

``` php
public function syncGroups(array $ids, bool $detaching = true);
```

*See description above.*


## Atorscho\Membership\Membership

``` php
public function createUser(array $attributes, Group|int|string $group = null): User;
```

Create a user and assign him to a specified group.

If no group specified, the default group *(see config)* will be used.

> **Note:** The second parameter may be an instance of `Atorscho\Membership\Groups\Group`, group ID or group handle.

---

``` php
public function createMember(array $attributes): User;
```

Create a member.

---

``` php
public function createModerator(array $attributes): User;
```

Create a moderator.

---

``` php
public function createSuperModerator(array $attributes): User;
```

Create a super moderator.

---

``` php
public function createAdministrator(array $attributes): User;
```

Create an administrator.

---

``` php
public function createOwner(array $attributes): User;
```

Create an owner.

---

``` php
public function currentUser(string $attribute = null, $default = ''): User|string;
```

If no arguments are given, return currently authenticated user's instance.

If `$attribute` argument is specified, it will return its value if not empty, otherwise the `$default` value.

In case `$attribute == 'email' | 'mail'` it will be automatically obfuscated.

> Use `html_entity_decode()` function if you do not need the email to be obfuscated.

---

``` php
public function currentUserHas(string $attribute): bool;
```

Determine if the authenticated user's attribute is not empty.

---

``` php
public function avatar(User $user = null): string;
```

If `$user` specified, this method will return an absolute URI to the user's avatar, otherwise it will use the authenticated user.

---

``` php
public function avatarExists(User $user = null): bool;
```

This method returns true if specified or authenticated user's avatar exists.

---

``` php
public function obfuscate(string $value): string;
```

This method obfuscates a given string.

*Based on the same function of the LaravelCollective HTML package.*

---

``` php
public function registerPermissions(Gate $gate)
```

Register user permissions using the Laravel Access Gate.

*Needed for the `@can` Blade directive.*
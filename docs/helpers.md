# Helpers

``` php
function current_user(string $attribute = null, $default = ''): User;
```

The same as `Membership::currentUser()`.

Returns authenticated user's instance or its attribute value.

---

``` php
function current_user_is(array|string $groups, bool $strict = true): bool;
```

The same as `$user->is()`.

Returns `true` if current user belongs to specified group(s).

---

``` php
function current_user_can(array|string $permissions, Model $model = null, string $column = null): bool;
```

The same as `$user->can()`.

Returns `true` if current user has specified permission(s).

---

``` php
function is_logged_in(): bool;
```

The same as `auth()->check()`.

Returns `true` if the user is authenticated.

---

``` php
function is_logged_out(): bool;
```

The same as `auth()->guest()`.

Returns `true` if the user is a guest.

---

``` php
function avatar(User $user = null): string;
```

The same as `Membership::avatar()`.

Returns an absolute URI to the specified or authenticated user's avatar.

---

``` php
function avatar_exists(User $user = null): string;
```

The same as `Membership::avatarExists()`.

Returns `true` if the specified or authenticated user's avatar.

---

``` php
function str_obfuscate(string $value): string;
```

The same as `Membership::obfuscate()`.

Returns an obfuscated string. Useful for e-mail addresses.
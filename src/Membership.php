<?php

namespace Atorscho\Membership;

use Atorscho\Membership\Groups\Group;
use Atorscho\Membership\Permissions\Permission;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Filesystem\Filesystem;

class Membership
{
    /**
     * @var Guard
     */
    private $auth;

    /**
     * @var Filesystem
     */
    private $file;

    /**
     * @var Permission
     */
    private $permissions;

    /**
     * @var Group
     */
    private $groups;

    /**
     * Membership constructor.
     *
     * @param Guard      $auth
     * @param Filesystem $file
     * @param Group      $groups
     * @param Permission $permissions
     */
    public function __construct(Guard $auth, Filesystem $file, Group $groups, Permission $permissions)
    {
        $this->auth        = $auth;
        $this->file        = $file;
        $this->permissions = $permissions;
        $this->groups      = $groups;
    }

    /**
     * Create a user and assign it to a group.
     *
     * @param array  $attributes The user attributes.
     * @param string $group      Group handle or group model [Optional]
     *
     * @return object
     */
    public function createUser(array $attributes, $group = null)
    {
        // Default group
        if (!$group) {
            $group = $this->groups->findOrFail(config('membership.groups.default'));
        }

        // Get the group by its handle
        if (is_string($group)) {
            $group = $this->groups->whereHandle($group)->firstOrFail();
        }

        // Get user model class name
        $class = config('membership.users.model');

        // Create the user and assign it to the specific group
        $user = $class::create($attributes);
        $user->assignTo($group);

        return $user;
    }

    /**
     * Create a member.
     *
     * @param array $attributes
     *
     * @return object
     */
    public function createMember(array $attributes)
    {
        return $this->createUser($attributes, 'members');
    }

    /**
     * Create a moderator.
     *
     * @param array $attributes
     *
     * @return object
     */
    public function createModerator(array $attributes)
    {
        return $this->createUser($attributes, 'moderators');
    }

    /**
     * Create a super-moderator.
     *
     * @param array $attributes
     *
     * @return object
     */
    public function createSuperModerator(array $attributes)
    {
        return $this->createUser($attributes, 'super-moderators');
    }

    /**
     * Create an administrator.
     *
     * @param array $attributes
     *
     * @return object
     */
    public function createAdministrator(array $attributes)
    {
        return $this->createUser($attributes, 'administrators');
    }

    /**
     * Create an owner.
     *
     * @param array $attributes
     *
     * @return object
     */
    public function createOwner(array $attributes)
    {
        return $this->createUser($attributes, 'owners');
    }

    /**
     * Get currently logged in user instance or its attribute value.
     *
     * @param string|null $attribute User column name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function currentUser($attribute = null)
    {
        if (auth()->guest()) {
            return null;
        }

        $user = $this->getCurrentUserInstance();

        if (!$attribute) {
            return $user;
        }

        if (in_array($attribute, ['email', 'mail'])) {
            return $this->obfuscate($user->{$attribute});
        }

        return $user->{$attribute} ?: null;
    }

    /**
     * Determine if the authenticated user's attribute is not empty.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function currentUserHas($attribute)
    {
        return auth()->guest() && $this->getCurrentUserInstance()->{$attribute};
    }

    /**
     * Get user's avatar. If none found, return the default one.
     *
     * @param object|null $user User model instance. [Optional]
     *
     * @return string
     */
    public function avatar($user = null)
    {
        if ($this->auth->guest()) {
            return '';
        }

        if (!$user) {
            $user = $this->currentUser();
        }

        $avatar = $this->getAvatarPath() . $user->avatar;

        // Default avatar if user has not chosen one
        if (!$this->avatarExists($user)) {
            return asset($this->getDefaultAvatarPath());
        }

        return asset($avatar);
    }

    /**
     * Check wheter user avatar exists.
     *
     * @param object|null $user User model instance. [Optional]
     *
     * @return bool
     */
    public function avatarExists($user = null)
    {
        if (!$user) {
            $user = $this->currentUser();
        }

        $avatar = $this->getAvatarPath() . $user->avatar;

        return $user->avatar && $this->file->exists($avatar);
    }

    /**
     * Obfuscate a string.
     *
     * (c) Laravel (Collective)
     *
     * @param string $value
     *
     * @return string
     */
    public function obfuscate($value)
    {
        $safe = '';

        foreach (str_split($value) as $letter) {
            if (ord($letter) > 128) {
                return $letter;
            }

            // To properly obfuscate the value, we will randomly convert each letter to
            // its entity or hexadecimal representation, keeping a bot from sniffing
            // the randomly obfuscated letters out of the string on the responses.
            switch (rand(1, 3)) {
                case 1:
                    $safe .= '&#' . ord($letter) . ';';
                    break;

                case 2:
                    $safe .= '&#x' . dechex(ord($letter)) . ';';
                    break;

                case 3:
                    $safe .= $letter;
            }
        }

        return $safe;
    }

    /**
     * Register Membership's permissions with Laravel's Gate.
     *
     * @param GateContract $gate
     */
    public function registerPermissions(GateContract $gate)
    {
        $permissions = $this->permissions->all();

        foreach ($permissions as $permission) {
            $gate->define($permission->handle, function ($user) use ($permission) {
                return $user->can($permission->handle);
            });
        }
    }

    /**
     * Get the default avatar path.
     *
     * @return string
     */
    protected function getDefaultAvatarPath()
    {
        return config('membership.users.avatar.default');
    }

    /**
     * Get path to the avatars upload folder.
     *
     * @return string
     */
    protected function getAvatarPath()
    {
        return trim(config('membership.users.avatar.path', " \t\n\r\0\x0B\/")) . '/';
    }

    /**
     * Get authenticated user's instance.
     *
     * @return object
     */
    protected function getCurrentUserInstance()
    {
        $class = config('membership.users.model');

        $user = $class::with('groups', 'permissions')->findOrFail($this->auth->id());

        return $user;
    }
}

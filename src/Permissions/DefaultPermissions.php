<?php

namespace Atorscho\Membership\Permissions;

use Illuminate\Database\Seeder;

class DefaultPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();

        $permissions = $this->permissions();

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }

    /**
     * The array of permissions.
     *
     * @return array
     */
    protected function permissions()
    {
        return [
            [
                'name'   => 'Users: Create',
                'handle' => 'create.users'
            ],
            [
                'name'   => 'Users: Edit',
                'handle' => 'edit.users'
            ],
            [
                'name'   => 'Users: Edit Own Profile',
                'handle' => 'edit.own.profile'
            ],
            [
                'name'   => 'Users: Delete',
                'handle' => 'delete.users'
            ],
            [
                'name'   => 'Users: Assign Groups',
                'handle' => 'assign.users.groups'
            ],
            [
                'name'   => 'Users: Assign Permissions',
                'handle' => 'assign.users.permissions'
            ],
            [
                'name'   => 'Groups: Create',
                'handle' => 'create.groups'
            ],
            [
                'name'   => 'Groups: Edit',
                'handle' => 'edit.groups'
            ],
            [
                'name'   => 'Groups: Delete',
                'handle' => 'delete.groups'
            ],
            [
                'name'   => 'Groups: Assign Permissions',
                'handle' => 'assign.groups.permissions'
            ],
            [
                'name'   => 'Permissions: Create',
                'handle' => 'create.permissions'
            ],
            [
                'name'   => 'Permissions: Edit',
                'handle' => 'edit.permissions'
            ],
            [
                'name'   => 'Permissions: Delete',
                'handle' => 'delete.permissions'
            ]
        ];
    }
}

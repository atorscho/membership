<?php

namespace Atorscho\Uservel\Permissions;

use Illuminate\Database\Seeder;

class DefaultPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();

        foreach ($this->permissions() as $permission) {
            Permission::create($permission);
        }
    }

    protected function permissions()
    {
        return [
            [
                'name'   => 'Create Users',
                'handle' => 'create.users'
            ],
            [
                'name'   => 'View Users',
                'handle' => 'view.users'
            ],
            [
                'name'   => 'Edit Users',
                'handle' => 'edit.users'
            ],
            [
                'name'   => 'Delete Users',
                'handle' => 'delete.users'
            ],
            [
                'name'   => 'Create Groups',
                'handle' => 'create.groups'
            ],
            [
                'name'   => 'View Groups',
                'handle' => 'view.groups'
            ],
            [
                'name'   => 'Edit Groups',
                'handle' => 'edit.groups'
            ],
            [
                'name'   => 'Delete Groups',
                'handle' => 'delete.groups'
            ],
            [
                'name'   => 'Create Permissions',
                'handle' => 'create.permissions'
            ],
            [
                'name'   => 'View Permissions',
                'handle' => 'view.permissions'
            ],
            [
                'name'   => 'Edit Permissions',
                'handle' => 'edit.permissions'
            ],
            [
                'name'   => 'Delete Permissions',
                'handle' => 'delete.permissions'
            ]
        ];
    }
}

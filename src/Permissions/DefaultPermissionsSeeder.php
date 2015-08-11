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
                'name'   => 'Show Users',
                'handle' => 'show.users'
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
                'name'   => 'Show Groups',
                'handle' => 'show.groups'
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
                'name'   => 'Show Permissions',
                'handle' => 'show.permissions'
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

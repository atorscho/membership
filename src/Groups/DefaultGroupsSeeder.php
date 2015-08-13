<?php

namespace Atorscho\Uservel\Groups;

use DB;
use Illuminate\Database\Seeder;

class DefaultGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::truncate();
        DB::table('group_permissions')->truncate();

        Group::unguard();

        foreach ($this->groups() as $default) {
            Group::create($default);
        }

        Group::reguard();
    }

    /**
     * Return default groups.
     *
     * @return array
     */
    protected function groups()
    {
        return [
            [
                'id'          => 1,
                'name'        => 'Members',
                'handle'      => 'members',
                'description' => 'Default group for all registered users.',
                'permissions' => 'view.users|view.groups|view.permissions'
            ],
            [
                'id'          => 2,
                'name'        => 'Moderators',
                'handle'      => 'moderators',
                'description' => 'Users with higher permissions and capabilities.',
                'permissions' => 'view.users|edit.users|view.groups|edit.groups|view.permissions'
            ],
            [
                'id'          => 3,
                'name'        => 'Administrators',
                'handle'      => 'admins',
                'description' => 'Users with all or almost all permissions.',
                'permissions' => 'create.users|view.users|edit.users|delete.users|create.groups|view.groups|edit.groups|delete.groups|create.permissions|view.permissions|edit.permissions'
            ],
            [
                'id'          => 4,
                'name'        => 'Super Administrators',
                'handle'      => 'superadmins',
                'description' => 'Owners of the site.',
                'permissions' => '*'
            ]
        ];
    }
}

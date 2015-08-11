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
                'permissions' => 'show.users|show.groups|show.permissions'
            ],
            [
                'id'          => 2,
                'name'        => 'Moderators',
                'handle'      => 'moderators',
                'description' => 'Users with higher permissions and capabilities.',
                'permissions' => 'show.users|edit.users|show.groups|edit.groups|show.permissions'
            ],
            [
                'id'          => 3,
                'name'        => 'Administrators',
                'handle'      => 'admins',
                'description' => 'Users with all or almost all permissions.',
                'permissions' => 'create.users|show.users|edit.users|delete.users|create.groups|show.groups|edit.groups|delete.groups|create.permissions|show.permissions|edit.permissions'
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

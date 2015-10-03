<?php

namespace Atorscho\Membership\Groups;

use Illuminate\Database\Seeder;

class DefaultGroups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::truncate();

        $groups = $this->groups();

        foreach ($groups as $group) {
            Group::create($group)->givePermissionTo($group['permissions']);
        }
    }

    /**
     * The array of groups and their permissions.
     *
     * @return array
     */
    protected function groups()
    {
        return [
            [
                'name'        => 'Members',
                'handle'      => 'members',
                'description' => 'Default group for registered users.',
                'permissions' => 'edit.own.profile'
            ],
            [
                'name'        => 'Moderators',
                'handle'      => 'moderators',
                'description' => 'Users with higher access level and permissions.',
                'permissions' => 'edit.users|edit.own.profile'
            ],
            [
                'name'        => 'Super Moderators',
                'handle'      => 'super-moderators',
                'description' => 'Moderators with extended permissions.',
                'permissions' => 'create.users|edit.users|edit.own.profile|edit.groups'
            ],
            [
                'name'        => 'Administrators',
                'handle'      => 'administrators',
                'description' => 'Users with highest access permissions.',
                'permissions' => '*'
            ],
            [
                'name'        => 'Owners',
                'handle'      => 'owners',
                'description' => 'Users with full access to the site.',
                'permissions' => '*'
            ]
        ];
    }
}

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

        $sets = $this->permissions();

        foreach ($sets as $set) {
            $permissionSet = PermissionSet::create($set);

            foreach ($set['permissions'] as $permission) {
                $permissionSet->permissions()->save(Permission::create($permission));
            }
        }
    }

    /**
     * Permissions and sets.
     *
     * @return array
     */
    protected function permissions()
    {
        return [
            [
                'name'        => 'Users',
                'handle'      => 'users',
                'permissions' => [
                    [
                        'name'   => 'Users: Create',
                        'handle' => 'create.users'
                    ],
                    [
                        'name'   => 'Users: Edit',
                        'handle' => 'edit.users'
                    ],
                    [
                        'name'   => 'Users: Delete',
                        'handle' => 'delete.users'
                    ],
                    [
                        'name'   => 'Users: Assign Permissions',
                        'handle' => 'assign.users.permissions'
                    ]
                ]
            ],
            [
                'name'        => 'Groups',
                'handle'      => 'groups',
                'permissions' => [
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
                    ]
                ]
            ],
            [
                'name'        => 'Permissions',
                'handle'      => 'permissions',
                'permissions' => [
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
                ]
            ],
            [
                'name'        => 'Other',
                'handle'      => 'other',
                'permissions' => [
                    [
                        'name'   => 'Access Admin CP',
                        'handle' => 'access.acp'
                    ]
                ]
            ]
        ];
    }
}

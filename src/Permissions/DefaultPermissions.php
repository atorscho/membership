<?php

namespace Atorscho\Membership\Permissions;

use DB;
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

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
                        'name'   => 'Create',
                        'handle' => 'create.users'
                    ],
                    [
                        'name'   => 'Edit',
                        'handle' => 'edit.users'
                    ],
                    [
                        'name'   => 'Delete',
                        'handle' => 'delete.users'
                    ],
                    [
                        'name'   => 'Assign Permissions',
                        'handle' => 'assign.users.permissions'
                    ]
                ]
            ],
            [
                'name'        => 'Groups',
                'handle'      => 'groups',
                'permissions' => [
                    [
                        'name'   => 'Create',
                        'handle' => 'create.groups'
                    ],
                    [
                        'name'   => 'Edit',
                        'handle' => 'edit.groups'
                    ],
                    [
                        'name'   => 'Delete',
                        'handle' => 'delete.groups'
                    ],
                    [
                        'name'   => 'Assign Permissions',
                        'handle' => 'assign.groups.permissions'
                    ]
                ]
            ],
            [
                'name'        => 'Permissions',
                'handle'      => 'permissions',
                'permissions' => [
                    [
                        'name'   => 'Create',
                        'handle' => 'create.permissions'
                    ],
                    [
                        'name'   => 'Edit',
                        'handle' => 'edit.permissions'
                    ],
                    [
                        'name'   => 'Delete',
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

<?php

namespace Atorscho\Membership;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class DefaultSeeder
 * In order to seed your "groups" and "permissions" table,
 * you can override this class' "groups()" method with your data.
 *
 * @package Atorscho\Membership
 * @author  Alex Torscho <contact@alextorscho.com>
 * @version 2.0.0
 */
class DefaultSeeder extends Seeder
{
    /**
     * @var array Groups to seed.
     */
    protected $groups = [];

    /**
     * DefaultSeeder constructor.
     */
    public function __construct(array $groups = [])
    {
        $this->groups = $groups;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = $this->groups ?: $this->groups();
        DB::table('group_permissions')->truncate();

        foreach ($groups as $group) {
            $permissions = $group['permissions'] ?? [];
            unset($group['permissions']);

            /** @var Group $groupModel */
            $groupModel = Group::firstOrCreate($group);

            foreach ($permissions as $permission) {
                $groupModel->grantPermissions(Permission::firstOrCreate($permission));
            }
        }
    }

    /**
     * Get an array of predefined groups with their permissions.
     */
    protected function groups(): array
    {
        return [
            [
                'name'        => 'Registered',
                'permissions' => [
                    [
                        'name'   => 'View Site',
                        'handle' => 'view',
                        'type'   => 'site'
                    ]
                ]
            ],
            [
                'name'        => 'Administrators',
                'handle'      => 'admins',
                'permissions' => [
                    [
                        'name'   => 'View Site',
                        'handle' => 'view',
                        'type'   => 'site'
                    ]
                ]
            ],
        ];
    }
}

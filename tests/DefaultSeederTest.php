<?php

namespace Atorscho\Membership\Tests;

use Atorscho\Membership\DefaultSeeder;

class DefaultSeederTest extends TestCase
{
    /** @test */
    public function it_seeds_groups_and_their_permissions()
    {
        $seeder = new DefaultSeeder([
            [
                'name' => 'Sample Group'
            ],
            [
                'name'        => 'Group with Permission',
                'permissions' => [
                    [
                        'name'   => 'Create Users',
                        'handle' => 'create',
                        'type'   => 'users'
                    ]
                ]
            ]
        ]);
        $seeder->run();

        $this->assertDatabaseHas('groups', [
            'name' => 'Sample Group'
        ]);
        $this->assertDatabaseHas('permissions', [
            'name' => 'Create Users'
        ]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id' => 2,
            'permission_id' => 1
        ]);
    }
}

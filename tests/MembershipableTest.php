<?php

namespace Atorscho\Membership\Tests;

class MembershipableTest extends TestCase
{
    /** @test */
    public function a_user_can_be_assigned_to_a_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $user->assignTo($group);
        $this->assertDatabaseHas('user_groups', [
            'user_id'  => 1,
            'group_id' => 1
        ]);
    }

    /** @test */
    public function a_user_can_be_unassigned_from_a_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $user->assignTo($group);
        $user->unassignFrom($group);
        $this->assertDatabaseMissing('user_groups', [
            'user_id'  => 1,
            'group_id' => 1
        ]);
    }

    /** @test */
    public function it_can_check_whether_the_user_belongs_to_a_group()
    {
        $user   = $this->createUser();
        $group  = $this->createGroup();
        $group2 = $this->createGroup();

        $user->assignTo($group);
        $this->assertTrue($user->isAssignedTo($group));
        $this->assertFalse($user->isAssignedTo($group2));

        $user->unassignFrom($group);
        $this->assertFalse($user->isAssignedTo($group));
    }

    /** @test */
    public function permissions_can_be_attached_to_the_user()
    {
        $user = $this->createUser();

        $user->attach($this->createPermission(), $this->createPermission());
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 1,
        ]);
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 2,
        ]);

        $user->attach([$this->createPermission()]);
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 3,
        ]);

        $user->attach(collect([$this->createPermission()]));
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 4,
        ]);
    }
}

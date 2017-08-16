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
    public function a_user_can_be_retracted_from_a_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $user->assignTo($group);
        $user->retractFrom($group);
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

        $user->retractFrom($group);
        $this->assertFalse($user->isAssignedTo($group));
    }

    /** @test */
    public function permissions_can_be_attached_to_the_user()
    {
        $user = $this->createUser();

        $user->grantPermissions($this->createPermission(), $this->createPermission());
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 1,
        ]);
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 2,
        ]);

        $user->grantPermissions([$this->createPermission()]);
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 3,
        ]);

        $user->grantPermissions(collect([$this->createPermission()]));
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 4,
        ]);
    }

    /** @test */
    public function permissions_can_be_detached_from_the_user()
    {
        $user = $this->createUser();

        $user->grantPermissions($this->createPermission(), $this->createPermission());
        $user->losePermissions(1, 2);
        $this->assertDatabaseMissing('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 1,
        ]);
        $this->assertDatabaseMissing('user_permissions', [
            'user_id'       => 1,
            'permission_id' => 2,
        ]);
    }

    /** @test */
    public function a_user_can_be_marked_as_leader_of_a_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $user->makeLeaderOf($group);
        $this->assertDatabaseHas('group_leaders', [
            'user_id'  => 1,
            'group_id' => 1
        ]);
    }

    /** @test */
    public function it_can_check_whether_the_user_is_a_leader_of_a_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $this->assertFalse($user->isLeaderOf($group));

        $user->makeLeaderOf($group);

        $this->assertTrue($user->isLeaderOf($group));
    }

    /** @test */
    public function it_checks_whether_the_user_has_permission_through_its_groups()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();
        $user->assignTo($group);
        $user->assignTo($this->createGroup());

        $group->grantPermissions(
            $perm1 = $this->createPermission(),
            $perm2 = $this->createPermission(),
            $perm3 = $this->createPermission()
        );

        $this->assertTrue($user->hasPermission($perm1->code));
        $this->assertFalse($user->hasPermission('some-inexistent-permission'));
    }

    /** @test */
    public function it_checks_whether_the_user_has_own_permission()
    {
        $user = $this->createUser();

        $user->grantPermissions(
            $perm1 = $this->createPermission(),
            $perm2 = $this->createPermission(),
            $perm3 = $this->createPermission()
        );

        $this->assertTrue($user->hasPermission($perm1->code));
        $this->assertFalse($user->hasPermission('some-inexistent-permission'));
    }

    /** @test */
    public function it_returns_user_primary_group()
    {
        $user = $this->createUser();
        $group = $this->createGroup();

        $group->assign($user, true);

        $this->assertEquals($group->id, $user->fresh()->primaryGroup->id);
    }

    /** @test */
    public function it_formats_user_name_according_to_the_primary_group_tags()
    {
        $user = $this->createUser(['name' => 'John']);
        $group = $this->createGroup(['open_tag' => '<span>', 'close_tag' => '</span>']);

        $this->assertEquals('', $user->formatted_name);

        $group->assign($user, true);

        \Config::shouldReceive('get')->once()->with('membership.users.name_column')->andReturn('name');

        $this->assertEquals('<span>John</span>', $user->fresh()->formatted_name);
    }
}

<?php

namespace Atorscho\Membership\Tests;

use Atorscho\Membership\Permission;

class GroupTest extends TestCase
{
    /** @test */
    public function it_automatically_fills_not_provided_handle_based_on_the_group_name()
    {
        $group = $this->createGroup(['name' => 'Some group', 'handle' => '']);
        $this->assertEquals('some-group', $group->handle);

        $group = $this->createGroup(['name' => 'Other group', 'handle' => null]);
        $this->assertEquals('other-group', $group->handle);

        $group = $this->createGroup(['name' => 'Third group']);
        $this->assertEquals('third-group', $group->handle);
    }

    /** @test */
    public function it_correctly_formats_the_handle()
    {
        $group = $this->createGroup(['name' => 'Another Group', 'handle' => 'Another Group']);

        $this->assertEquals('another-group', $group->handle);
    }

    /** @test */
    public function it_can_format_group_name_using_tags()
    {
        $group = $this->createGroup([
            'name'      => 'Members',
            'handle'    => '',
            'open_tag'  => '<span class="color: #f40;">',
            'close_tag' => '</span>'
        ]);

        $this->assertEquals('<span class="color: #f40;">Members</span>', $group->formatted_name);
    }

    /** @test */
    public function a_list_of_permissions_can_be_attached_to_the_group()
    {
        $group       = $this->createGroup(['name' => 'Admins']);
        $permission  = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $group->grantPermissions($permission, $permission2);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 1,
        ]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 2,
        ]);
    }

    /** @test */
    public function an_array_of_permissions_can_be_attached_to_the_group()
    {
        $group       = $this->createGroup(['name' => 'Admins']);
        $permission  = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $group->grantPermissions([$permission, $permission2]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 1,
        ]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 2,
        ]);
    }

    /** @test */
    public function a_collection_of_permissions_can_be_attached_to_the_group()
    {
        $group = $this->createGroup(['name' => 'Admins']);
        $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $group->grantPermissions(Permission::all());
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 1,
        ]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 2,
        ]);
    }

    /** @test */
    public function it_can_attach_permissions_to_the_group_using_their_keys_or_handles()
    {
        $group = $this->createGroup(['name' => 'Admins']);
        $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $group->grantPermissions('users.create', 2);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 1,
        ]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 2,
        ]);
    }

    /** @test */
    public function permissions_can_be_detached_from_the_group()
    {
        $group = $this->createGroup(['name' => 'Admins']);
        $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $group->grantPermissions(Permission::all());
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 1
        ]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 2
        ]);

        $group->losePermissions(Permission::find(1));
        $this->assertDatabaseMissing('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 1
        ]);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 2
        ]);

        $group->losePermissions(2);
        $this->assertDatabaseMissing('group_permissions', [
            'group_id'      => 1,
            'permission_id' => 2
        ]);
    }

    /** @test */
    public function a_user_can_be_assigned_to_the_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup(['name' => 'Admins']);

        $group->assign($user);
        $this->assertDatabaseHas('user_groups', [
            'user_id'  => 1,
            'group_id' => 1,
        ]);
    }

    /** @test */
    public function a_user_can_be_retracted_from_the_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup(['name' => 'Admins']);

        $group->assign($user);
        $this->assertDatabaseHas('user_groups', [
            'user_id'  => 1,
            'group_id' => 1,
        ]);

        $group->retract($user);
        $this->assertDatabaseMissing('user_groups', [
            'user_id'  => 1,
            'group_id' => 1,
        ]);
    }

    /** @test */
    public function it_can_check_whether_the_group_has_a_given_user()
    {
        $user  = $this->createUser();
        $group = $this->createGroup(['name' => 'Admins']);

        $group->assign($user);
        $this->assertTrue($group->hasAssigned($user));

        $group->retract($user);
        $this->assertFalse($group->hasAssigned($user));
    }

    /** @test */
    public function a_group_can_have_leaders()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $group->addLeader($user);
        $this->assertDatabaseHas('group_leaders', [
            'user_id'  => 1,
            'group_id' => 1,
        ]);
    }

    /** @test */
    public function it_can_check_whether_the_user_is_a_leader_of_a_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $this->assertFalse($group->hasLeader($user));

        $group->addLeader($user);

        $this->assertTrue($group->hasLeader($user));
    }

    /** @test */
    public function a_group_cannot_exceed_its_limit()
    {
        $group = $this->createGroup([
            'limit' => 5
        ]);

        $group->assign($this->createUser());
        $group->assign($this->createUser());
        $group->assign($this->createUser());
        $group->assign($this->createUser());
        $group->assign($this->createUser());

        $this->assertCount(5, $group->users);

        $group->assign($this->createUser());
        $group->assign($this->createUser());

        $this->assertCount(5, $group->fresh()->users);
    }

    /** @test */
    public function it_sets_users_primary_group_when_assigning_him_to_a_group()
    {
        $user  = $this->createUser();
        $group = $this->createGroup();

        $group->assign($user, true);

        $this->assertEquals($group->id, $user->primary_group_id);
    }
}

<?php

namespace Atorscho\Membership\Tests;

use Atorscho\Membership\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionTest extends TestCase
{
    /** @test */
    public function it_automatically_fills_not_provided_handle_based_on_the_permission_name()
    {
        $permission = $this->createPermission(['name' => 'Some permission', 'handle' => '']);
        $this->assertEquals('some-permission', $permission->handle);

        $permission = $this->createPermission(['name' => 'Other permission', 'handle' => null]);
        $this->assertEquals('other-permission', $permission->handle);

        $permission = $this->createPermission(['name' => 'Third permission']);
        $this->assertEquals('third-permission', $permission->handle);
    }

    /** @test */
    public function it_correctly_formats_the_handle()
    {
        $permission = $this->createPermission(['name' => 'Another Permission', 'handle' => 'Another Permission']);

        $this->assertEquals('another-permission', $permission->handle);
    }

    /** @test */
    public function it_returns_permission_code()
    {
        $permission = $this->createPermission(['name' => 'Create', 'type' => 'users']);

        $this->assertEquals('users.create', $permission->code);
    }

    /** @test */
    public function permission_code_is_present_in_the_serialized_form()
    {
        $permission = $this->createPermission(['name' => 'Create', 'type' => 'users']);

        $this->assertArrayHasKey('code', $permission->toArray());
    }

    /** @test */
    public function it_can_search_for_permissions_using_their_type()
    {
        $this->createPermission(['type' => 'users']);
        $this->createPermission(['type' => 'users']);
        $this->createPermission();
        $this->createPermission();
        $this->createPermission();

        $this->assertEquals(5, Permission::count());
        $this->assertEquals(2, Permission::searchType('users')->count());
    }

    /** @test */
    public function it_can_search_for_permissions_using_their_type_and_handle()
    {
        $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $this->createPermission(['handle' => 'update', 'type' => 'users']);
        $this->createPermission(['handle' => 'delete', 'type' => 'users']);
        $this->createPermission();
        $this->createPermission();

        $this->assertEquals(5, Permission::count());
        $this->assertInstanceOf(Model::class, Permission::search('users.create'));

        $this->expectException('Illuminate\Database\Eloquent\ModelNotFoundException');
        Permission::search('users.inexistent');

        $this->expectException('InvalidArgumentException');
        Permission::search('users');
    }

    /** @test */
    public function a_permission_can_be_assigned_to_a_group()
    {
        $group       = $this->createGroup(['name' => 'Admins']);
        $permission  = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $permission->assignTo($group);
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => $group->id,
            'permission_id' => $permission->id
        ]);

        $permission2->assignTo('admins');
        $this->assertDatabaseHas('group_permissions', [
            'group_id'      => $group->id,
            'permission_id' => $permission2->id
        ]);

        $this->expectException(ModelNotFoundException::class);
        $permission2->assignTo('unknown-group');
    }

    /** @test */
    public function it_can_check_whether_the_permission_belongs_to_a_given_group()
    {
        $group       = $this->createGroup(['name' => 'Admins']);
        $permission  = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $permission->assignTo($group);
        $this->assertTrue($permission->isAssignedTo($group));
        $this->assertFalse($permission2->isAssignedTo('admins'));

        $permission2->assignTo('admins');
        $this->assertTrue($permission2->isAssignedTo('admins'));
    }

    /** @test */
    public function a_permission_can_be_unassigned_from_a_group()
    {
        $group       = $this->createGroup(['name' => 'Admins']);
        $permission = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $permission->assignTo($group);
        $permission2->assignTo($group);
        $this->assertTrue($permission->isAssignedTo($group));
        $this->assertTrue($permission2->isAssignedTo($group));

        $permission->unassignFrom($group);
        $this->assertFalse($permission->isAssignedTo($group));
        $this->assertTrue($permission2->isAssignedTo($group));
    }

    /** @test */
    public function a_permission_can_be_granted_to_a_user()
    {
        $user        = $this->createUser();
        $permission  = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $permission->grantTo($user);
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => $user->id,
            'permission_id' => $permission->id
        ]);

        $permission2->grantTo(1);
        $this->assertDatabaseHas('user_permissions', [
            'user_id'       => $user->id,
            'permission_id' => $permission2->id
        ]);

        $permission2->grantTo(3);
        $this->assertDatabaseMissing('user_permissions', [
            'user_id'       => 3,
            'permission_id' => $permission2->id
        ]);
    }

    /** @test */
    public function it_can_check_whether_the_permission_belongs_to_a_given_user()
    {
        $user        = $this->createUser();
        $permission  = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $permission->grantTo($user);
        $this->assertTrue($permission->isGrantedTo($user));
        $this->assertFalse($permission2->isGrantedTo($user));

        $permission2->grantTo(1);
        $this->assertTrue($permission2->isGrantedTo($user));
    }

    /** @test */
    public function a_permission_can_be_ungranted_from_a_user()
    {
        $user        = $this->createUser();
        $permission  = $this->createPermission(['handle' => 'create', 'type' => 'users']);
        $permission2 = $this->createPermission(['handle' => 'update', 'type' => 'users']);

        $permission->grantTo($user);
        $this->assertTrue($permission->isGrantedTo($user));
        $this->assertFalse($permission2->isGrantedTo($user));

        $permission->ungrantFrom($user);
        $this->assertFalse($permission->isGrantedTo($user));
        $this->assertFalse($permission2->isGrantedTo($user));
    }
}

<?php

namespace Atorscho\Membership\Tests;

use Facades\{
    Atorscho\Membership\Membership
};
use Illuminate\Contracts\Auth\Authenticatable;

class MembershipTest extends TestCase
{
    /** @test */
    public function it_checks_whether_the_user_is_authenticated()
    {
        $this->assertFalse(Membership::isLoggedIn());

        $this->actingAs($this->createUser())
             ->assertTrue(Membership::isLoggedIn());
   }

    /** @test */
    public function it_checks_whether_the_user_is_guest()
    {
        $this->assertTrue(Membership::isGuest());

        $this->actingAs($this->createUser())
             ->assertFalse(Membership::isGuest());
   }

    /** @test */
    public function it_returns_currently_authenticated_user()
    {
        $this->assertNull(Membership::user());

        $this->actingAs($this->createUser())
             ->assertInstanceOf(Authenticatable::class, Membership::user());
    }

    /** @test */
    public function it_returns_field_value_of_the_authenticated_user()
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $this->assertEquals($user->name, Membership::user('name'));
    }
}

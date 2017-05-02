<?php

namespace Atorscho\Membership\Tests\Unit;

use Atorscho\Membership\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GroupTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_belongs_to_a_group()
    {
        dd($this->userModel);
    }
}

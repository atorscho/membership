<?php

namespace Atorscho\Membership\Tests;

use Illuminate\Database\Eloquent\Model;

class TestCase extends \Tests\TestCase
{
    /**
     * @var Model
     */
    protected $userModel;

    protected function setUp()
    {
        parent::setUp();

        $this->userModel = $this->userModel();
    }

    /**
     * Get the user model.
     */
    protected function userModel()
    {
        return config('auth.providers.users.model');
    }
}

<?php

namespace Atorscho\Membership\Tests;

use Atorscho\Membership\Group;
use Atorscho\Membership\Permission;
use Faker\Factory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;

class TestCase extends \Tests\TestCase
{
    /**
     * @var Model
     */
    protected $userModel;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Prepare tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userModel = $this->userModel();
        $this->faker     = Factory::create();

        $this->artisan('migrate');
        $this->runPackageMigrations();
    }

    /**
     * Run package's migrations.
     */
    protected function runPackageMigrations(): void
    {
        $fileSystem = new Filesystem;

        foreach ($fileSystem->files(__DIR__ . "/../migrations") as $file) {
            $file    = file_get_contents($file);
            $matched = [];
            preg_match('/class ([\w]+) extends/', $file, $matched);

            (new $matched[1])->up();
        }
    }

    /**
     * Get the user model.
     */
    protected function userModel()
    {
        return config('auth.providers.users.model');
    }

    /**
     * Generate a random user.
     */
    protected function createUser(array $attributes = []): Authenticatable
    {
        return $this->userModel::create([
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => bcrypt('secret')
        ]);
    }

    /**
     * Generate a random group.
     */
    protected function createGroup(array $attributes = []): Group
    {
        $word = $this->faker->word;

        $default = [
            'name'      => $word,
            'open_tag'  => null,
            'close_tag' => null
        ];

        if (!array_key_exists('name', $attributes)) {
            $default['handle'] = $word;
        }

        return Group::create(array_merge($default, $attributes));
    }

    /**
     * Generate a random permission.
     */
    protected function createPermission(array $attributes = []): Permission
    {
        $word = $this->faker->word;

        $default = [
            'name' => $word,
            'type' => str_plural($this->faker->word),
        ];

        if (!array_key_exists('name', $attributes)) {
            $default['handle'] = $word;
        }

        return Permission::create(array_merge($default, $attributes));
    }
}

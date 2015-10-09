<?php

namespace Atorscho\Membership\Setup;

use Atorscho\Membership\Groups\DefaultGroups;
use Atorscho\Membership\Membership;
use Atorscho\Membership\MembershipServiceProvider;
use Atorscho\Membership\Permissions\DefaultPermissions;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;

class Installer
{
    /**
     * Number of steps for the progress bars.
     */
    const STEPS = 5;

    /**
     * @var InstallUserMembershipSystem
     */
    protected $command;

    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var Membership
     */
    protected $membership;

    /**
     * Installer constructor.
     *
     * @param InstallUserMembershipSystem $command
     * @param Filesystem                  $file
     * @param Membership                  $membership
     */
    public function __construct(InstallUserMembershipSystem $command, Filesystem $file, Membership $membership)
    {
        $this->command    = $command;
        $this->file       = $file;
        $this->membership = $membership;
    }

    /**
     * Run the installation process.
     */
    public function install()
    {
        $bar = $this->command->getOutput()->createProgressBar(static::STEPS);

        // 1. Publish configs and migrations
        $this->publishFiles();
        $bar->advance();

        // 2. Run migrations
        $this->runMigrations();
        $bar->advance();

        // 3. Add default permissions
        $this->addDefaultPermissions();
        $bar->advance();

        // 4. Add default groups
        $this->addDefaultGroups();
        $bar->advance();

        // 5. Ask for superuser credentials
        $this->createSuperuser();
        $bar->advance();

        // 6. Register Membership's permissions with Laravel's Gate
        $this->registerPermissions();
        $bar->advance();

        $bar->finish();
    }

    /**
     * Publish migration and configuration files.
     */
    protected function publishFiles()
    {
        $this->command->callSilent('vendor:publish', [
            '--provider' => MembershipServiceProvider::class
        ]);

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->command->comment('1. Configuration and migrations files have been successfully published.');
        }
    }

    /**
     * Run migrations.
     */
    protected function runMigrations()
    {
        $this->command->callSilent('migrate');

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->command->comment('2. All database tables have been successfully migrated.');
        }
    }

    /**
     * Add the default permissions to the DB.
     */
    protected function addDefaultPermissions()
    {
        $this->command->callSilent('db:seed', [
            '--class' => DefaultPermissions::class
        ]);

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->command->comment('3. Default permissions have been installed.');
        }
    }

    /**
     * Add the default groups to the DB.
     */
    protected function addDefaultGroups()
    {
        $this->command->callSilent('db:seed', [
            '--class' => DefaultGroups::class
        ]);

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->command->comment('4. Default groups have been installed.');
        }
    }

    /**
     * Ask for credentials to create a superuser.
     *
     * @return object
     */
    protected function createSuperuser()
    {
        // Ask for credentials
        $username = $this->command->ask('Enter desired name for the super-user');
        $email    = $this->command->ask('Now the email address');
        $password = $this->askForPassword();

        // Validate the given credentials
        $validator = \Validator::make(compact('email'), ['email' => 'email']);

        if ($validator->fails()) {
            $this->command->error('You must specify a valid email address.');
            $this->createSuperuser();
        }

        $table = $this->checkUsersTableExistence();
        list($usernameColumn, $emailColumn, $passwordColumn) = $this->checkUserColumnsExistence($table);

        // Create an owner
        $user = $this->membership->createOwner([
            $usernameColumn => $username,
            $emailColumn    => $email,
            $passwordColumn => bcrypt($password)
        ]);

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->command->comment('5. Super-user has been successfully created.');
        }

        return $user;
    }

    /**
     * Ask for password.
     *
     * @return string
     */
    protected function askForPassword()
    {
        $password        = $this->command->secret('Choose a strong password');
        $passwordConfirm = $this->command->secret('Confirm it by typing the password once again');

        if ($password !== $passwordConfirm) {
            $this->command->error('Your passwords did not match. Try again.');

            return $this->askForPassword();
        }

        return $password;
    }

    /**
     * Register Membership's permissions
     * by editing the `AuthServiceProvider.php` file.
     *
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function registerPermissions()
    {
        $path     = app_path('Providers/AuthServiceProvider.php');
        $register = 'Membership::registerPermissions($gate);';
        $search   = 'parent::registerPolicies($gate);';
        $replace  = sprintf("%s\n\n%s\\%s", $search, str_repeat(' ', 8), $register);
        $contents = $this->file->get($path);

        // Check whether permissions are already registered
        if (str_contains($contents, $register)) {
            return 0;
        }

        // Register permissions
        return $this->file->put($path, str_replace($search, $replace, $contents));
    }

    /**
     * Get the minimum verbosity level.
     *
     * @return int
     */
    protected function minimumVerbosity()
    {
        return OutputInterface::VERBOSITY_VERBOSE;
    }

    /**
     * Get current verbosity.
     *
     * @return int
     */
    protected function verbosity()
    {
        return $this->command->getOutput()->getVerbosity();
    }

    /**
     * Check whether the "users" table exists.
     * If not, prompt to type the new name.
     *
     * @return string
     */
    protected function checkUsersTableExistence()
    {
        return $this->command->ask('What is your users table name?', 'users');
    }

    /**
     * Check for "users" table columns existence.
     *
     * @param string $table
     *
     * @return array
     */
    protected function checkUserColumnsExistence($table)
    {
        $columns = ['username', 'email', 'password'];

        foreach ($columns as $key => $column) {
            if (!\Schema::hasColumn($table, $column)) {
                $columns[$key] = $this->command->ask("It seems you do not have the \"{$column}\" column in your \"{$table}\" table. Enter the new column name", $column);
            }
        }

        return $columns;
    }
}

<?php

namespace Atorscho\Membership\Setup;

use Atorscho\Membership\Groups\DefaultGroups;
use Atorscho\Membership\Membership;
use Atorscho\Membership\MembershipServiceProvider;
use Atorscho\Membership\Permissions\DefaultPermissions;
use Atorscho\Membership\Setup\Installer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class InstallUserMembershipSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install user membership system.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Installer $installer)
    {
        $installer->install();

        die;

        // 1. Publish configs and migrations
        $this->publishFiles();

        // 2. Run migrations
        $this->runMigrations();

        // 3. Add default permissions
        $this->addDefaultPermissions();

        // 4. Add default groups
        $this->addDefaultGroups();

        // 5. Ask for superuser credentials
        $this->createSuperuser();
    }

    /**
     * Publish migration and configuration files.
     */
    protected function publishFiles()
    {
        $this->callSilent('vendor:publish', [
            '--provider' => MembershipServiceProvider::class
        ]);

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->comment('1. Configuration and migrations files have been successfully published.');
        }
    }

    /**
     * Run migrations.
     */
    protected function runMigrations()
    {
        $this->callSilent('migrate');

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->comment('2. All database tables have been successfully migrated.');
        }
    }

    /**
     * Add the default permissions to the DB.
     */
    protected function addDefaultPermissions()
    {
        $this->call('db:seed', [
            '--class' => DefaultPermissions::class
        ]);

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->comment('3. Default permissions have been installed.');
        }
    }

    /**
     * Add the default groups to the DB.
     */
    protected function addDefaultGroups()
    {
        $this->call('db:seed', [
            '--class' => DefaultGroups::class
        ]);

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->comment('4. Default groups have been installed.');
        }
    }

    /**
     * Ask for credentials to create a superuser.
     *
     * @param Membership $membership
     *
     * @return object
     */
    protected function createSuperuser(Membership $membership)
    {
        // Ask for credentials
        $username = $this->ask('Enter desired name for the super-user');
        $email    = $this->ask('Now the email address');
        $password = $this->askForPassword();

        // Validate the given credentials
        $validator = \Validator::make(compact('email'), ['email' => 'email']);

        if ($validator->fails()) {
            $this->error('You must specify a valid email address.');
            $this->createSuperuser($membership);
        }

        // Create an owner
        $user = $membership->createOwner(compact('username', 'email', 'password'));

        if ($this->verbosity() >= $this->minimumVerbosity()) {
            $this->comment('5. Super-user has been successfully created.');
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
        $password        = $this->secret('Choose a strong password');
        $passwordConfirm = $this->secret('Confirm it by typing the password once again');

        if ($password !== $passwordConfirm) {
            $this->error('Your passwords did not match. Try again.');

            return $this->askForPassword();
        }

        return $password;
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
        return $this->getOutput()->getVerbosity();
    }
}

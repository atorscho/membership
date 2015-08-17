<?php

namespace Atorscho\Uservel\Console;

use Atorscho\User;
use Atorscho\Uservel\Groups\DefaultGroupsSeeder;
use Atorscho\Uservel\Permissions\DefaultPermissionsSeeder;
use Atorscho\Uservel\Users\UserFormRequest;
use Atorscho\Uservel\UservelServiceProvider;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Validator;

// todo - Ask to create a superuser

class InstallUservel extends Command
{
    /**
     * Number of steps for the progress bars.
     */
    const STEPS = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uservel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Uservel package.';

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
    public function handle()
    {
        $verbosityLevel = OutputInterface::VERBOSITY_VERBOSE;
        $verbosity      = $this->getOutput()->getVerbosity();

        // Start progress bar
        $this->output->progressStart(self::STEPS);

        // 1. Publish configs and migrations
        /*$this->callSilent('vendor:publish', [
            '--provider' => UservelServiceProvider::class
        ]);
        if ($verbosity >= $verbosityLevel) {
            $this->comment('1. Configuration and migrations files have been successfully published.');
        }
        $this->output->progressAdvance();

        // 2. Run migrations
        $this->callSilent('migrate');
        if ($verbosity >= $verbosityLevel) {
            $this->comment('2. All tables have been successfully migrated.');
        }
        $this->output->progressAdvance();

        // 3. Add default permissions
        $this->call('db:seed', [
            '--class' => DefaultPermissionsSeeder::class
        ]);
        if ($verbosity >= $verbosityLevel) {
            $this->comment('3. Default permissions have been added.');
        }
        $this->output->progressAdvance();

        // 4. Add default groups
        $this->call('db:seed', [
            '--class' => DefaultGroupsSeeder::class
        ]);
        if ($verbosity >= $verbosityLevel) {
            $this->comment('4. Default groups have been added.');
        }
        $this->output->progressAdvance();*/

        // 5. Ask for superuser credentials
        $this->createSuperuser();

        $this->output->progressFinish();

        /*$this->info('Uservel has been successfully installed!');*/
    }

    /**
     * Ask for credentials to create a superuser.
     */
    protected function createSuperuser()
    {
        $username = $this->ask('Enter the desired username for the superuser');
        $email    = $this->ask('Now the email address');
        $password = $this->askForPassword();

        $rules     = (new UserFormRequest())->rules();
        $validator = Validator::make(compact('email'), ['email' => $rules['email']]);

        if ($validator->fails()) {
            $this->error('You must specify a valid email address.');
            $this->createSuperuser();
        }

        $attributes = compact('username', 'email', 'password') + ['groups' => 'superadmins'];

        dd($attributes);

        return User::create($attributes);
    }

    /**
     * Ask for password.
     *
     * @return string
     */
    protected function askForPassword()
    {
        $password        = $this->secret('Choose a strong password');
        $passwordConfirm = $this->secret('Confirm by typing it once again');

        if ($password != $passwordConfirm) {
            $this->error('Your passwords did not match. Try again.');

            return $this->askForPassword();
        }

        return $password;
    }
}

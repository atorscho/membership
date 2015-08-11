<?php

namespace Atorscho\Uservel\Console;

use Atorscho\Uservel\Groups\DefaultGroupsSeeder;
use Atorscho\Uservel\Permissions\DefaultPermissionsSeeder;
use Atorscho\Uservel\UservelServiceProvider;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class InstallUservel extends Command
{
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
        // Start progress bar
        $this->output->progressStart(4);

        // 1. Publish configs and migrations
        $this->callSilent('vendor:publish', [
            '--provider' => UservelServiceProvider::class
        ]);
        if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->comment('1. Configuration and migrations files have been successfully published.');
        }
        $this->output->progressAdvance();

        // 2. Run migrations
        $this->callSilent('migrate');
        if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->comment('2. All tables have been successfully migrated.');
        }
        $this->output->progressAdvance();

        // 3. Add default groups
        $this->call('db:seed', [
            '--class' => DefaultGroupsSeeder::class
        ]);
        if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->comment('3. Default groups have been added.');
        }
        $this->output->progressAdvance();

        // 4. Add default permissions
        $this->call('db:seed', [
            '--class' => DefaultPermissionsSeeder::class
        ]);
        if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->comment('4. Default permissions have been added.');
        }
        $this->output->progressAdvance();

        $this->output->progressFinish();

        $this->info('Uservel has been successfully installed!');
    }
}

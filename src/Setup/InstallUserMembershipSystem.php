<?php

namespace Atorscho\Membership\Setup;

use Atorscho\Membership\Groups\DefaultGroups;
use Atorscho\Membership\Membership;
use Atorscho\Membership\MembershipServiceProvider;
use Atorscho\Membership\Permissions\DefaultPermissions;
use Atorscho\Membership\Setup\Installer;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Builder;
use Illuminate\Filesystem\Filesystem;
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
     * @param Filesystem $file
     * @param Membership $membership
     *
     * @return mixed
     */
    public function handle(Filesystem $file, Membership $membership)
    {
        $installer = new Installer($this, $file, $membership);
        $installer->install();
    }

    /**
     * Get output property.
     *
     * @return \Illuminate\Console\OutputStyle
     */
    public function getOutput()
    {
        return $this->output;
    }
}

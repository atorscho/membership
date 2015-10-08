<?php

namespace Atorscho\Membership\Setup;

use Atorscho\Membership\Groups\DefaultGroups;
use Atorscho\Membership\Membership;
use Atorscho\Membership\MembershipServiceProvider;
use Atorscho\Membership\Permissions\DefaultPermissions;
use \Illuminate\Console\OutputStyle;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Validation\Factory;
use Symfony\Component\Console\Output\OutputInterface;

class Installer
{
    /**
     * @var Filesystem
     */
    private $file;

    /**
     * Installer constructor.
     *
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    /**
     * Run the installation process.
     */
    public function install()
    {
        $this->registerPermissions();
    }

    /**
     * Register Membership's permissions in `AuthServiceProvider.php` file.
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
}

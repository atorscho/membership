<?php

use Illuminate\Database\Seeder;

class DefaultGroups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }

    protected function groups()
    {
        return [
            [
                'name'        => 'Members',
                'handle'      => 'members',
                'description' => 'Default group for registered users.'
            ],
            [
                'name'        => 'Moderators',
                'handle'      => 'moderators',
                'description' => "Site's moderators."
            ],
            [
                'name'        => 'Administrators',
                'handle'      => 'administrators',
                'description' => "Site's administrators."
            ],
            [
                'name'        => 'Owners',
                'handle'      => 'owners',
                'description' => 'Users with full access to the site.'
            ]
        ];
    }
}

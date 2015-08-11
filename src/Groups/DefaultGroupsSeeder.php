<?php

namespace Atorscho\Uservel\Groups;

use Illuminate\Database\Seeder;

class DefaultGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::truncate();

        /*foreach ($this->defaults() as $default) {
            Group::create($default);
        }*/

    }

    /**
     * Return default groups.
     *
     * @return array
     */
    protected function defaults()
    {
        return [
            [
                'name'   => 'Members',
                'handle' => 'members'
            ],
            [
                'name'   => 'Moderators',
                'handle' => 'moderators'
            ],
            [
                'name'   => 'Administrators',
                'handle' => 'administrators'
            ]
        ];
    }
}

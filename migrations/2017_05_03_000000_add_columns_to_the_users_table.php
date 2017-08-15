<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUserPermissionsTable
 *
 * @package Atorscho\Membership
 * @author  Alex Torscho <contact@alextorscho.com>
 * @version 2.0.0
 */
class AddColumnsToTheUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config = require __DIR__ . '/../config/membership.php';

        Schema::table($config['users']['table'], function (Blueprint $table) use ($config) {
            $table->unsignedInteger('primary_group_id')->nullable()->default($config['groups']['default']);

            $table->foreign('primary_group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $config = require __DIR__ . '/../config/membership.php';

        Schema::table($config['users']['table'], function (Blueprint $table) {
            $table->dropForeign('primary_group_id');
            $table->dropColumn('primary_group_id');
        });  }
}

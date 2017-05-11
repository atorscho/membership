<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUserGroupsTable
 *
 * @package Atorscho\Membership
 * @author  Alex Torscho <contact@alextorscho.com>
 * @version 2.0.0
 */
class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('group_id');

            $table->foreign('user_id')->references('id')->on(config('membership.users.table'));
            $table->foreign('group_id')->references('id')->on('groups');

            $table->unique(['user_id', 'group_id']);
            $table->index(['user_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
    }
}

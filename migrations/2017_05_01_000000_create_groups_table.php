<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('handle')->index()->unique();
            $table->string('open_tag')->nullable();
            $table->string('close_tag')->nullable();
        });

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
        Schema::dropIfExists('groups');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('handle')->index()->unique();
            $table->string('type');
        });

        Schema::create('group_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('permission_id');

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('permission_id')->references('id')->on('permissions');

            $table->unique(['group_id', 'permission_id']);
            $table->index(['group_id', 'permission_id']);
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('permission_id');

            $table->foreign('user_id')->references('id')->on(config('membership.users.table'));
            $table->foreign('permission_id')->references('id')->on('permissions');

            $table->unique(['user_id', 'permission_id']);
            $table->index(['user_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('group_permissions');
        Schema::dropIfExists('permissions');
    }
}

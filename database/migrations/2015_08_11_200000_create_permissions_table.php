<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->integer('set_id')->unsigned()->nullable()->index();
            $table->string('name');
            $table->string('handle')->unique()->index();

            $table->foreign('set_id')->references('id')->on('permission_sets')->onDelete('cascade');
        });

        Schema::create('group_permissions', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->index();
            $table->integer('permission_id')->unsigned()->index();

            $table->primary(['group_id', 'permission_id']);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            $table->primary(['permission_id', 'user_id']);
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on(config('auth.table'))->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_permissions');
        Schema::drop('group_permissions');
        Schema::drop('permissions');
    }
}

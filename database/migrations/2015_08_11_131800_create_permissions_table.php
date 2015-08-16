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
            $table->string('name');
            $table->string('handle')->unique()->index();
        });

        Schema::create('group_permissions', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->integer('permission_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            $table->unique([
                'group_id',
                'permission_id'
            ]);
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on(config('uservel.users.table'))->onDelete('cascade');

            $table->unique([
                'permission_id',
                'user_id'
            ]);
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

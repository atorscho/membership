<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMembershipColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('membership.users.table') ?: 'users';

        Schema::table($table, function (Blueprint $table) {
            $table->unsignedInteger('primary_group_id')->nullable();
            $table->unsignedInteger('identifying_group_id')->nullable();

            $table->foreign('primary_group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('identifying_group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = config('membership.users.table') ?: 'users';

        Schema::table($table, function (Blueprint $table) {
            $table->dropColumn('primary_group_id');
            $table->dropColumn('identifying_group_id');

            $table->dropForeign('primary_group_id');
            $table->dropForeign('identifying_group_id');
        });
    }
}

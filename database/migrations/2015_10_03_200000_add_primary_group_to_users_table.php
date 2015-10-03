<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryGroupToUsersTable extends Migration
{
    /**
     * Table name for users.
     *
     * @var string
     */
    protected $tableName;

    /**
     * AddPrimaryGroupToUsersTable constructor.
     */
    public function __construct()
    {
        $this->tableName = config('membership.users.table');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->integer('primary_group_id')->nullable()->unsigned()->index()->after('id');

            $table->foreign('primary_group_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->dropForeign("{$this->tableName}_primary_group_id_foreign");
            $table->dropColumn('primary_group_id');
        });
    }
}

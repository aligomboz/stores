<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreeIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('storee_id')->nullable()->after('remember_token');
                $table->foreign('storee_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('storee_id');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiTokenToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('users', function (Blueprint $table) {
            $table->string('api_token')->nullable();
            $table->dateTime('api_token_expiration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('users', function (Blueprint $table) {
            $tabel->dropColumn('api_token');
            $tabel->dropColumn('api_token_expiration');
        });
    }
}

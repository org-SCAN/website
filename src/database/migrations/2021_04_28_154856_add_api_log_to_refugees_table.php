<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiLogToRefugeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refugees', function (Blueprint $table) {
            $table->foreignUuid("api_log")->default("seeder"); //TODO : retirer ça quand on aura plus les seeders !
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refugees', function (Blueprint $table) {
            $table->dropColumn("api_log");
        });
    }
}

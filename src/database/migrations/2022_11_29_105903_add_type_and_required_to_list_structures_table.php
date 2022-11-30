<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_structures', function (Blueprint $table) {
            $table->string("type")->nullable()->default(null);
            $table->boolean("required")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_structures', function (Blueprint $table) {
            $table->dropColumn("type");
            $table->dropColumn("required");
        });
    }
};

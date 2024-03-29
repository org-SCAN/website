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
        Schema::table('duplicates', function (Blueprint $table) {
            $table->foreignUuid('selected_duplicate_algorithm_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('duplicates', function (Blueprint $table) {
            $table->dropColumn('selected_duplicate_algorithm_id');
        });
    }
};

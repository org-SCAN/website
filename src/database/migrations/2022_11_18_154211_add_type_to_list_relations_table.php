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
        Schema::table('list_relations', function (Blueprint $table) {
            $table->string('type')->default('unilateral');
            $table->renameColumn('relation', 'relation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_relations', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->renameColumn('relation_id', 'relation');
        });
    }
};

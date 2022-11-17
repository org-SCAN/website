<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('command_runs',
            function (Blueprint $table) {
                $table->uuid("id")->primary();
                $table->timestamps();
                $table->softDeletes();
                $table->timestamp("started_at")->nullable()->default(null);
                $table->timestamp("ended_at")->nullable()->default(null);
                $table->string("command");
                $table->string("status");

            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('command_runs');
    }
};

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
        Schema::create('duplicates',
            function (Blueprint $table) {
                $table->uuid("id")->primary();
                $table->timestamps();
                $table->softDeletes();
                $table->foreignUuid("person1_id");
                $table->foreignUuid("person2_id");
                $table->foreignUuid("crew_id");
                $table->foreignUuid("command_run_id")->nullable();
                $table->double("similarity")->nullable();
                $table->boolean("resolved")->default(false);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('duplicates');
    }
};

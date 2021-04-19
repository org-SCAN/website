<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->string("id");
            $table->timestamps();
            $table->foreignUuid("language");
            $table->foreignUuid("field_label");
            $table->foreignUuid("field_id");
            $table->foreignUuid("list");
            $table->foreignUuid("translation");
            $table->boolean("deleted")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translations');
    }
}

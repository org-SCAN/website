<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name")->unique();
            $table->foreignUuid("event_type_id");
            $table->foreignUuid("event_subtype_id");
            $table->foreignUuid("country_id");
            $table->string("location_details");
            $table->dateTimeTz("start_date", $precision = 2);
            $table->dateTimeTz("stop_date", $precision = 2);
            $table->string("latitude");
            $table->string("longitude");
            $table->longText("description");
            $table->foreignUuid("apiLog_id");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}

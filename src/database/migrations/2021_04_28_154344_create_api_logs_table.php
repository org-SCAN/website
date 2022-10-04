<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->uuid("id")->primary()->unique();
            $table->timestamps();
            $table->foreignUuid("user_id");
            $table->foreignUuid("crew_id");
            $table->string("application_id");
            $table->string("api_type");
            $table->ipAddress("ip")->nullable();
            $table->string("response")->default("success");
            $table->string("model")->nullable();
            $table->string("http_method");
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
        Schema::dropIfExists('api_logs');
    }
}

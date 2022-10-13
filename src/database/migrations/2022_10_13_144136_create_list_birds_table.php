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
        Schema::create('list_birds', function (Blueprint $table) {
            $table->uuid("id");
            $table->timestamps();
            $table->softDeletes();
            $table->string("name");
			$table->string("size");
			$table->string("location");
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_birds');
    }
};

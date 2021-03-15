<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateGenderPhpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	protected $table_name;
	public function __construct(){
	
		$this->table_name = "gender_php";
	}

    public function up()
    {
        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
		$table->string($this->table_name."__"."id", 36)->unique();
		$table->index($this->table_name."__"."short");
		$table->index($this->table_name."__"."full");

	});

	DB::table($this->table_name)->insert([
		[$this->table_name."__"."id"=> (string) Str::uuid(),$this->table_name."__"."short"=> "F", $this->table_name."__"."full"=> "Female"],
		[$this->table_name."__"."id"=> (string) Str::uuid(),$this->table_name."__"."short"=> "M", $this->table_name."__"."full"=> "Male"],
		[$this->table_name."__"."id"=> (string) Str::uuid(),$this->table_name."__"."short"=> "NB", $this->table_name."__"."full"=> "Non-Binary"],
		[$this->table_name."__"."id"=> (string) Str::uuid(),$this->table_name."__"."short"=> "O", $this->table_name."__"."full"=> "Other"]	
	]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table_name);
    }
}

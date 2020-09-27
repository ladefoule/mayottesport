<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCrudTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('crud_tables', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('nom')->unique('table_unique')->index();
			$table->boolean('crudable')->nullable();
			$table->string('tri_defaut')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('crud_tables');
	}

}

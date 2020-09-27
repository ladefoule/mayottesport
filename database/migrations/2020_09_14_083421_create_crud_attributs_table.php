<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCrudAttributsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('crud_attributs', function(Blueprint $table)
		{
			$table->bigIncrements('id');
            $table->unsignedBigInteger('crud_table_id');
            $table->foreign('crud_table_id')->references('id')->on('crud_tables');
            $table->string('attribut');
			$table->string('label');
            $table->unsignedBigInteger('attribut_crud_table_id')->nullable();
            $table->foreign('attribut_crud_table_id')->references('id')->on('crud_tables');
            $table->boolean('optionnel')->nullable();
            $table->text('data_msg')->nullable();
            $table->unique(['crud_table_id','attribut']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('crud_attributs');
	}

}

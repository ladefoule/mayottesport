<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJourneesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('journees', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('numero');
            $table->date('date');
            $table->unsignedBigInteger('saison_id');
            $table->foreign('saison_id')->references('id')->on('saisons');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('journees');
	}

}

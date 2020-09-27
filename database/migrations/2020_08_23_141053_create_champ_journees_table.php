<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChampJourneesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('champ_journees', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('numero');
            $table->date('date');
            $table->unsignedBigInteger('champ_saison_id');
            $table->foreign('champ_saison_id')->references('id')->on('champ_saisons');
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
		Schema::drop('champ_journees');
	}

}

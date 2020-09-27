<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoupeNomToursTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupe_nom_tours', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('nom')->unique('coupe_nom_tour_unique');
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
		Schema::drop('coupe_nom_tours');
	}

}

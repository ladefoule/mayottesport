<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoupeToursTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupe_tours', function(Blueprint $table)
		{
            $table->bigIncrements('id');
			$table->integer('numero');
            $table->date('date');
            $table->unsignedBigInteger('coupe_saison_id');
            $table->foreign('coupe_saison_id')->references('id')->on('coupe_saisons');
            $table->unsignedBigInteger('nom_tour_id');
            $table->foreign('coupe_nom_tour_id')->references('id')->on('coupe_nom_tours');
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
		Schema::drop('coupe_tours');
	}

}

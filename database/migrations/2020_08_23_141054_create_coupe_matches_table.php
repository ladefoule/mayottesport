<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoupeMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupe_matches', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->string('uniqid')->unique();
            $table->unsignedBigInteger('equipe_id_dom');
            $table->foreign('equipe_id_dom')->references('id')->on('equipes');
            $table->unsignedBigInteger('equipe_id_ext');
            $table->foreign('equipe_id_ext')->references('id')->on('equipes');
            $table->unsignedBigInteger('terrain_id');
            $table->foreign('terrain_id')->references('id')->on('terrains');
            $table->unsignedBigInteger('coupe_tour_id');
            $table->foreign('coupe_tour_id')->references('id')->on('coupe_tours');
			$table->date('date')->nullable();
			$table->string('heure', 5)->nullable();
			$table->boolean('acces_bloque')->nullable();
            $table->integer('nb_modifs')->nullable();
            $table->integer('score_eq_dom')->nullable();
			$table->integer('score_eq_ext')->nullable();
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
		Schema::drop('coupe_matches');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChampMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('champ_matches', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('equipe_id_dom');
            $table->foreign('equipe_id_dom')->references('id')->on('equipes');
            $table->unsignedBigInteger('equipe_id_ext');
            $table->foreign('equipe_id_ext')->references('id')->on('equipes');
            $table->unsignedBigInteger('terrain_id');
            $table->foreign('terrain_id')->references('id')->on('terrains');
            $table->unsignedBigInteger('champ_journee_id');
            $table->foreign('champ_journee_id')->references('id')->on('champ_journees');
			$table->date('date')->nullable();
			$table->time('heure')->nullable();
			$table->boolean('acces_bloque')->nullable();
			$table->integer('nb_modifs')->default(0);
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
		Schema::drop('champ_matches');
	}

}

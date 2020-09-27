<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChampSaisonEquipeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('champ_saison_equipe', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->unsignedBigInteger('equipe_id');
            $table->foreign('equipe_id')->references('id')->on('equipes');
            $table->unsignedBigInteger('champ_saison_id');
            $table->foreign('champ_saison_id')->references('id')->on('champ_saisons');
            $table->unique(['equipe_id','champ_saison_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('champ_saison_equipe');
	}

}

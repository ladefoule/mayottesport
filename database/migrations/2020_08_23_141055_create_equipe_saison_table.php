<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEquipeSaisonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equipe_saison', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->unsignedBigInteger('equipe_id');
            $table->foreign('equipe_id')->references('id')->on('equipes');
            $table->unsignedBigInteger('saison_id');
            $table->foreign('saison_id')->references('id')->on('saisons');
            $table->unique(['equipe_id','saison_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('equipe_saison');
	}

}

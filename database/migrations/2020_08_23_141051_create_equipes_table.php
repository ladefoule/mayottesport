<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEquipesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equipes', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->string('uniqid')->unique();
            $table->string('nom');
            $table->unsignedBigInteger('sport_id');
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('restrict');
            $table->unsignedBigInteger('ville_id');
            $table->foreign('ville_id')->references('id')->on('villes')->onDelete('restrict');
			$table->string('nom_complet')->nullable();
			$table->boolean('feminine')->nullable();
			$table->boolean('non_mahoraise')->nullable();
            $table->timestamps();
            $table->unique(['nom', 'sport_id'], 'equipe_unique_par_sport');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('equipes');
	}

}

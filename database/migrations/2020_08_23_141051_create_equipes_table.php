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
            $table->string('nom')->unique('equipe_unique');
            $table->unsignedBigInteger('sport_id');
            $table->foreign('sport_id')->references('id')->on('sports');
			$table->string('nom_complet')->nullable();
			$table->boolean('feminine')->nullable();
			$table->boolean('non_mahoraise')->nullable();
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
		Schema::drop('equipes');
	}

}

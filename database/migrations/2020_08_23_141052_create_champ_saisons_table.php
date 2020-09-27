<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChampSaisonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('champ_saisons', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('annee_debut');
			$table->integer('annee_fin');
            $table->integer('nb_journees');
            $table->boolean('finie')->nullable();
            $table->unsignedBigInteger('championnat_id');
            $table->foreign('championnat_id')->references('id')->on('championnats');
            $table->unsignedBigInteger('champ_bareme_id');
            $table->foreign('champ_bareme_id')->references('id')->on('champ_baremes');
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
		Schema::drop('champ_saisons');
	}

}

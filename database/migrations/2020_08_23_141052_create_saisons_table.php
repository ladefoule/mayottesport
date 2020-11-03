<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaisonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('saisons', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('annee_debut');
			$table->integer('annee_fin');
            $table->integer('nb_journees');
            $table->boolean('finie')->nullable();
            $table->unsignedBigInteger('competition_id');
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->unsignedBigInteger('bareme_id')->nullable();
            $table->foreign('bareme_id')->references('id')->on('baremes');
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
		Schema::drop('saisons');
	}

}

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
            $table->integer('nb_montees')->nullable();
            $table->integer('nb_descentes')->nullable();
            $table->boolean('finie')->default(0);
            $table->unsignedBigInteger('competition_id');
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('restrict');
            $table->unsignedBigInteger('bareme_id')->nullable();
            $table->foreign('bareme_id')->references('id')->on('baremes')->onDelete('restrict');
            $table->unsignedBigInteger('vainqueur')->nullable();
            $table->foreign('vainqueur')->references('id')->on('equipes')->onDelete('restrict');
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

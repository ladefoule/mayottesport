<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoupeSaisonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupe_saisons', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->date('annee_debut');
			$table->date('annee_fin');
            $table->integer('nb_tours');
            $table->unsignedBigInteger('coupe_id');
            $table->foreign('coupe_id')->references('id')->on('coupes');
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
		Schema::drop('coupe_saisons');
	}

}

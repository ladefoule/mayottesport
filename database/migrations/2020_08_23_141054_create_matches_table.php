<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('matches', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->string('uniqid')->unique();
            $table->unsignedBigInteger('equipe_id_dom');
            $table->foreign('equipe_id_dom')->references('id')->on('equipes')->onDelete('restrict');
            $table->unsignedBigInteger('equipe_id_ext');
            $table->foreign('equipe_id_ext')->references('id')->on('equipes')->onDelete('restrict');
            $table->unsignedBigInteger('terrain_id')->nullable();
            $table->foreign('terrain_id')->references('id')->on('terrains')->onDelete('restrict');
            $table->unsignedBigInteger('journee_id');
            $table->foreign('journee_id')->references('id')->on('journees')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
			$table->date('date')->nullable();
			$table->string('heure', 5)->nullable();
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
		Schema::drop('matches');
	}

}

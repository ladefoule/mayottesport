<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompetitionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('competitions', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('nom');
            $table->string('slug');
            $table->string('nom_complet');
            $table->string('slug_complet');
			$table->integer('type');
			$table->integer('home_position')->nullable();
            $table->integer('index_position')->nullable();
            $table->unsignedBigInteger('sport_id');
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('restrict');
            $table->timestamps();
            $table->unique(['sport_id', 'nom']);
            $table->unique(['sport_id', 'slug']);
            $table->unique(['sport_id', 'nom_complet']);
            $table->unique(['sport_id', 'slug_complet']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('competitions');
	}

}

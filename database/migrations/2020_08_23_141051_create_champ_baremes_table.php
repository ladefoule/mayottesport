<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChampBaremesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('champ_baremes', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('nom');
			$table->boolean('victoire')->nullable();
			$table->boolean('nul')->nullable();
            $table->boolean('defaite')->nullable();
            $table->unsignedBigInteger('sport_id');
            $table->foreign('sport_id')->references('id')->on('sports');
            $table->timestamps();
            $table->unique(['sport_id', 'nom']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('champ_baremes');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('competition_id');
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->unsignedBigInteger('saison_id');
            $table->foreign('saison_id')->references('id')->on('saisons')->onDelete('cascade');
            $table->unsignedBigInteger('equipe_id');
            $table->foreign('equipe_id')->references('id')->on('equipes')->onDelete('cascade');
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
        Schema::dropIfExists('champions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampMatchInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champ_match_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('champ_match_id');
            $table->foreign('champ_match_id')->references('id')->on('champ_matches')->onDelete('cascade');
            $table->integer('information');
            $table->string('valeur');
            $table->unique(['champ_match_id', 'information']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('champ_match_infos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoupeMatchInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupe_match_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('coupe_match_id')->unique();
            $table->foreign('coupe_match_id')->references('id')->on('coupe_matches')->onDelete('cascade');
            $table->integer('information');
            $table->string('valeur');
            $table->unique(['coupe_match_id', 'information']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupe_match_infos');
    }
}

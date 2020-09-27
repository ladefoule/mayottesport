<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampBaremeInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champ_bareme_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('champ_bareme_id')->unique();
            $table->foreign('champ_bareme_id')->references('id')->on('champ_baremes')->onDelete('cascade');
            $table->integer('information');
            $table->string('valeur');
            $table->unique(['champ_bareme_id', 'information']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('champ_bareme_infos');
    }
}

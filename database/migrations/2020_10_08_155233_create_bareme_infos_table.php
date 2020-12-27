<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaremeInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bareme_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bareme_id')->unique();
            $table->foreign('bareme_id')->references('id')->on('baremes')->onDelete('cascade');
            $table->integer('propriete_id');
            $table->string('valeur')->nullable();
            $table->unique(['bareme_id', 'propriete_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bareme_infos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrudAttributInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crud_attribut_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('crud_attribut_id');
            $table->foreign('crud_attribut_id')->references('id')->on('crud_attributs')->onDelete('cascade');
            $table->integer('information_id');
            $table->string('valeur');
            $table->unique(['crud_attribut_id','information_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crud_attribut_infos');
    }
}

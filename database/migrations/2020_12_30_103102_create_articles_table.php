<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('img')->nullable();
            $table->string('uniqid')->unique();
            $table->string('titre');
            $table->string('slug');
            $table->integer('home_position')->nullable();
            $table->boolean('fil_actu')->nullable();
            $table->text('preambule');
            $table->longText('article')->nullable();
            $table->boolean('valide');
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('user_update_id')->nullable();
            $table->foreign('user_update_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('articles');
    }
}

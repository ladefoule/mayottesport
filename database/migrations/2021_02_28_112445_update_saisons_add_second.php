<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSaisonsAddSecond extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saisons', function (Blueprint $table) {
            $table->unsignedBigInteger('second')->after('equipe_id')->nullable();
            $table->foreign('second')->references('id')->on('equipes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saisons', function (Blueprint $table) {
            $table->dropForeign(['second']);
            $table->dropColumn('second');
        });
    }
}

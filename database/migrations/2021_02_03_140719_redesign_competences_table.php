<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RedesignCompetencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('identifier');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('competence_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competence_id');
            $table->foreign('php artisan make:migration create_flights_table')
                ->references('id')->on('competences');
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            $table->unique(['offer_id', 'competence_id']);
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
        Schema::dropIfExists('competence_offer');
        Schema::dropIfExists('competences');
    }
}

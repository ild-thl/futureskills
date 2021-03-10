<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_relations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            $table->unsignedBigInteger('offerrelated_id');
            $table->foreign('offerrelated_id')
                ->references('id')->on('offers');
            $table->unique(['offer_id', 'offerrelated_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_relations');
    }
}

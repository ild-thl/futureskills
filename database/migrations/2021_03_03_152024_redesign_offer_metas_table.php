<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RedesignOfferMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->string('description')->unique();
            $table->enum('datatype', ['varchar(191)', 'tinyint(1)', 'int(11)', 'text']);
            $table->timestamps();
        });

        Schema::create('meta_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meta_id');
            $table->foreign('meta_id')
                ->references('id')->on('metas');
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            $table->string('value')->nullable();
            $table->unique(['offer_id', 'meta_id']);
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
        Schema::dropIfExists('meta_offer');
        Schema::dropIfExists('metas');
    }
}

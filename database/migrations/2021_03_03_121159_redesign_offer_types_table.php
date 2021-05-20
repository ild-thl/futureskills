<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RedesignOfferTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offertypes', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->timestamps();
        });
        
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('offertype_id');
            $table->foreign('offertype_id')->references('id')->on('offertypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_offertype_id_foreign');
            $table->dropColumn('offertype_id');
        });
        Schema::dropIfExists('offertypes');
    }
}

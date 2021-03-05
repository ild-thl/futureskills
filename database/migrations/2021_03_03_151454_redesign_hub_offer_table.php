<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Offer;

class RedesignHubOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $offers = Offer::all();

        Schema::create('hub_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            $table->integer('sort_flag')->nullable();
            $table->text('keywords')->nullable();
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        #Migration der Daten
        foreach ( $offers as $offer ) {
            DB::table('hub_offers')->insert([
                'offer_id' => $offer->id,
                'sort_flag' => $offer->sort_flag,
                'visible' => $offer->active,
                ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hub_offers');
    }
}

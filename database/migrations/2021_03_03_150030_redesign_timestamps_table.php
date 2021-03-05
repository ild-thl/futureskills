<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Offer;

class RedesignTimestampsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $offers = Offer::all();

        Schema::create('timestamps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                ->references('id')->on('offers')->unique();
            $table->timestamp('executed_from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('executed_until')->nullable();
            $table->timestamp('listed_from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('listed_until')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        #Migration der Daten
        foreach ( $offers as $offer ) {
            DB::table('timestamps')->insert([
                'offer_id' => $offer->id,
                'executed_from' => $offer->executed_from,
                'executed_until' => $offer->executed_until,
                'listed_from' => $offer->listed_from,
                'listed_until' => $offer->listed_until,
                'active' => $offer->active,
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
        Schema::dropIfExists('timestamps');
    }
}

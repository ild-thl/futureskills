<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Offer;

class RedesignCompetencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $offers = Offer::all();

        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('competence_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competence_id');
            $table->foreign('competence_id')
                ->references('id')->on('competences');
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            $table->unique(['offer_id', 'competence_id']);
            $table->timestamps();

        });
        /*
        #Seeder lässt sich nicht über Migration aufrufen. Zur Vereinfachung der Migration werden Daten hier eingefügt.
        DB::table('competences')->insert(['title' => 'tech']);
        DB::table('competences')->insert(['title' => 'digital']);
        DB::table('competences')->insert(['title' => 'classic']);
        */
        foreach ( $offers as $offer ) {
            if ( $offer->competence_tech === 1 ) {
                DB::table('competence_offer')->insert(['competence_id' => 1, 'offer_id' => $offer->id]);
            }
            if ( $offer->competence_digital === 1 ) {
                DB::table('competence_offer')->insert(['competence_id' => 2, 'offer_id' => $offer->id]);
            }
            if ( $offer->competence_classic === 1 ) {
                DB::table('competence_offer')->insert(['competence_id' => 3, 'offer_id' => $offer->id]);
            }
        }
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

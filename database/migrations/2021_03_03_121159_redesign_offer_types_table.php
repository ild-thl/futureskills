<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Offer;

class RedesignOfferTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $offers = Offer::all();

        Schema::create('offertypes', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->timestamps();
        });
        /*
        $types = array (
            "online-course" => 1,
            "webinar" => 2,
            "presence-event" => 3,
            "presence-series" => 4,
            "self-study-course" => 5,
            "course-package" => 6,
            "blended-learning" => 7,
        );

        #Seeder lässt sich nicht über Migration aufrufen. Zur Vereinfachung der Migration werden Daten hier eingefügt.
        foreach ( $types as $identifier => $id ) {
           DB::table('offertypes')->insert(['identifier' => $identifier]);
        }
        */
        #Anlegen der Spalte ohne FK
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('offertype_id');
        });
        /*
        #Migration der Daten
        foreach ( $offers as $offer ) {
            DB::update("update offers set offertype_id = " . $types[$offer->type] . " where id = " . $offer->id );
        }
        */
        #Änderung zum FK
        Schema::table('offers', function (Blueprint $table) {
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

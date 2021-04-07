<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Offer;

class RedesignLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $offers = Offer::all();

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->timestamps();
        });
        /*
        $languages = array (
            "de" => 1,
            "en" => 2,
        );
        
        foreach ( $languages as $identifier => $id ) {
            DB::table('languages')->insert(['identifier' => $identifier]);
         }
        */
        #Anlegen der Spalte ohne FK
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('language_id');
        });

        /*
        #Migration der Daten
        foreach ( $offers as $offer ) {
            $lang = '';
            switch ( strtolower($offer->language)) {
                case 'deutsch':
                    $lang = 1;
                    break;
                case 'englisch':
                    $lang = 2;
                    break;
                case 'english':
                    $lang = 2;
                    break;
                default:
                    $lang = 1;
                    break;
            }

            DB::update("update offers set language_id = " . $lang . " where id = " . $offer->id );
        }
        */
        #Änderung zum FK
        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('language_id')->references('id')->on('languages');
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
            $table->dropForeign('offers_language_id_foreign');
            $table->dropColumn('language_id');
        });
        Schema::dropIfExists('languages');
    }
}

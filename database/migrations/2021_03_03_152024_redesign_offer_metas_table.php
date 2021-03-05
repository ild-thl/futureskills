<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Offer;

class RedesignOfferMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $offers = Offer::all();

        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->enum('datatype', ['varchar(191)', 'tinyint(1)', 'int(11)', 'text']);
            $table->timestamps();
        });

        Schema::create('offer_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meta_id');
            $table->foreign('meta_id')
                ->references('id')->on('metas');
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            $table->string('value')->nullable();
            $table->timestamps();
        });

        $metas = array (
            "sponsor" => [1, 'varchar(191)'],
            "exam" => [2, 'varchar(191)'],
            "requirements" => [3, 'text'],
            "niveau" => [4, 'varchar(191)'],
            "location" => [5, 'varchar(191)'],
            "ects" => [6, 'int(11)'],
            "time_requirement" => [7, 'varchar(191)'],
        );

        foreach ( $metas as $description => $data ) {
            DB::table('metas')->insert(['description' => $description, 'datatype' => $data[1]]);
         }

        foreach ( $offers as $offer ) {
            $values = array(
                "sponsor" => $offer->sponsor,
                "exam" => $offer->exam,
                "requirements" => $offer->requirements,
                "niveau" => $offer->niveau,
                "ects" => $offer->ects,
                "time_requirement" -> $offer->time_requirement
            );
            foreach ( $values as $description => $value ) {
                if ( $value !== null ) {
                    DB::table('offer_meta')->insert([
                        'meta_id' => $metas[$description][0],
                        'offer_id' => $offer->id,
                        'value' => $value
                    ]);
                }
            }
            DB::update("update offers set externalId = " . $offer->ext_id . " where id = " . $offer->id );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_meta');
        Schema::dropIfExists('metas');
    }
}

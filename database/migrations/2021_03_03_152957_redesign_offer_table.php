<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Offer;

class RedesignOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $offers = Offer::all();

        Schema::table('offers', function (Blueprint $table) {
            $table->string('externalId')->nullable();
            $table->unique(['institution_id', 'externalId']);
        });

        foreach ( $offers as $offer ) {
            if ( isset ( $offer->ext_id ) ) {
                DB::update("update offers set externalId='" . $offer->ext_id . "' where id = " . $offer->id );
            }
        }

        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('executed_from');
            $table->dropColumn('executed_until');
            $table->dropColumn('listed_from');
            $table->dropColumn('listed_until');
            $table->dropColumn('sponsor');
            $table->dropColumn('exam');
            $table->dropColumn('requirements');
            $table->dropColumn('niveau');
            $table->dropColumn('ects');
            $table->dropColumn('type');
            $table->dropColumn('ext_id');
            $table->dropColumn('competence_tech');
            $table->dropColumn('competence_digital');
            $table->dropColumn('competence_classic');
            $table->dropColumn('active');
            $table->dropColumn('sort_flag');
            $table->dropColumn('language');
            $table->dropColumn('time_requirement');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // this is not to be reversed.
    }
}

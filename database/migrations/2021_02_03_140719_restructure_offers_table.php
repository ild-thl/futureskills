<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RestructureOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        DB::table('competences')->insert(['title' => 'tech']);
        DB::table('competences')->insert(['title' => 'digital']);
        DB::table('competences')->insert(['title' => 'classic']);

        Schema::create('competence_offer', function (Blueprint $table) {
            $table->id();
            $table->foreign('competence_id')
                ->references('id')->on('competences');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            $table->timestamps();
        });
*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

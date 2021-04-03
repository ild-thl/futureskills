<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competence_offer', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->foreign('offer_id')
            ->references('id')->on('offers')
            ->onDelete('cascade')
            ->change();
        });

        Schema::table('huboffers', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->foreign('offer_id')
            ->references('id')->on('offers')
            ->onDelete('cascade')
            ->unique()
            ->change();
        });

        Schema::table('timestamps', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->foreign('offer_id')
            ->references('id')->on('offers')
            ->onDelete('cascade')
            ->unique()
            ->change();
        });

        Schema::table('meta_offer', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->foreign('offer_id')
            ->references('id')->on('offers')
            ->onDelete('cascade')
            ->change();
        });

        Schema::table('offer_relations', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->foreign('offer_id')
            ->references('id')->on('offers')
            ->onDelete('cascade')
            ->change();

            $table->dropForeign(['offerrelated_id']);
            $table->foreign('offerrelated_id')
            ->references('id')->on('offers')
            ->onDelete('cascade')
            ->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // not to be reversed
    }
}

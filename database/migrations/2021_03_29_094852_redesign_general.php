<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RedesignGeneral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->string('identifier');
            $table->string('description');
            $table->dropColumn('title');
        });
        
        Schema::table('offertypes', function (Blueprint $table) {
            $table->string('description');
        });
        
        Schema::table('languages', function (Blueprint $table) {
            $table->string('description');
        });

        # IDs sind fest
        DB::update('update competences set identifier = ? where id = 1', ['tech']);
        DB::update('update competences set identifier = ? where id = 2', ['digital']);
        DB::update('update competences set identifier = ? where id = 3', ['classic']);

        DB::update('update competences set description = ? where id = 1', ['Tech-Kurse']);
        DB::update('update competences set description = ? where id = 2', ['Digital Basic-Kurse']);
        DB::update('update competences set description = ? where id = 3', ['Classic-Kurse']);

        DB::update('update languages set description = ? where id = 1', ['Deutsch']);
        DB::update('update languages set description = ? where id = 2', ['Englisch']);

        DB::update('update offertypes set description = ? where id = 1', ['Online-Kurs']);
        DB::update('update offertypes set description = ? where id = 2', ['Webinar']);
        DB::update('update offertypes set description = ? where id = 3', ['Präsenzveranstaltung']);
        DB::update('update offertypes set description = ? where id = 4', ['Präsenzveranstaltungsreihe']);
        DB::update('update offertypes set description = ? where id = 5', ['Selbstlernkurs']);
        DB::update('update offertypes set description = ? where id = 6', ['Kurspaket']);
        DB::update('update offertypes set description = ? where id = 7', ['Blended Learning']);

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

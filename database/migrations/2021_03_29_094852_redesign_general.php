<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

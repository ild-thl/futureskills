<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('url', 2083)->nullable();
            $table->integer('sort_flag')->nullable();
        });
        DB::statement("ALTER TABLE offers MODIFY COLUMN type ENUM('online-course', 'webinar','presence-event','presence-series', 'self-study-course')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function($table) {
            $table->dropColumn('url');
            $table->dropColumn('sort_flag');
        });
        DB::statement("ALTER TABLE offers MODIFY COLUMN type ENUM('online-course', 'webinar','presence-event','presence-series')");
    }
}

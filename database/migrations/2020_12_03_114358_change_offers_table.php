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
            $table->boolean('competence_tech')->nullable();
            $table->boolean('competence_digital')->nullable();
            $table->boolean('competence_classic')->nullable();
        });
        DB::statement("ALTER TABLE offers MODIFY COLUMN type ENUM('online-course', 'webinar','presence-event','presence-series', 'self-study-course', 'course-package')");
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
            $table->dropColumn('competence_tech');
            $table->dropColumn('competence_digital');
            $table->dropColumn('competence_classic');
        });
        DB::statement("ALTER TABLE offers MODIFY COLUMN type ENUM('online-course', 'webinar','presence-event','presence-series')");
    }
}

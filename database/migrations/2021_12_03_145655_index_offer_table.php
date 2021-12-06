<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class IndexOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `offers` ADD FULLTEXT INDEX offer_description_index (description)');
	    DB::statement('ALTER TABLE `offers` ADD FULLTEXT INDEX offer_title_index (title)');
        DB::statement('ALTER TABLE `offers` ADD FULLTEXT INDEX offer_author_index (author)');
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

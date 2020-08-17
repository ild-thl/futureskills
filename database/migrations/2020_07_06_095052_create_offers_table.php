<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->enum('type', ['online-course', 'webinar','presence-event','presence-series'])->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('institution_id');
            $table->string('subtitle')->nullable();
            $table->string('language')->default('Deutsch');
            $table->string('hashtag')->nullable();
            $table->integer('ects')->nullable();
            $table->string('time_requirement')->nullable();
            $table->timestamp('executed_from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('executed_until')->nullable();
            $table->timestamp('listed_from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('listed_until')->nullable();
            $table->string('author')->nullable();
            $table->string('sponsor')->nullable();
            $table->string('exam')->nullable();
            $table->text('requirements')->nullable();
            $table->string('niveau')->nullable();
            $table->string('target_group')->nullable();
            $table->foreign('institution_id')->references('id')->on('institutions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApikeysInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_key_institution', function (Blueprint $table) {
          $table->id();
          $table->timestamps();
          $table->unsignedInteger('api_key_id');
          $table->foreign('api_key_id')
              ->references('id')->on('api_keys');
          $table->unsignedBigInteger('institution_id');
          $table->foreign('institution_id')
              ->references('id')->on('institutions');
          $table->unique(['api_key_id', 'institution_id']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_key_institution');
    }
}

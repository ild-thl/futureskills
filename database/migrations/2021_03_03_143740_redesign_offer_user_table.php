<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RedesignOfferUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('offer_user'); # just in case
        Schema::dropIfExists('subscriptions');

        Schema::create('offer_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('offer_id');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('offer_id')
                ->references('id')->on('offers');
        });
        # Keine Daten zum Migrieren vorhanden
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_user');
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('offer_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('offer_id')
                ->references('id')->on('offers')->onDelete('cascade');
        });
    }
}

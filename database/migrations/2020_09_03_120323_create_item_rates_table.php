<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedInteger('item_id')->index();
            $table->unsignedInteger('rating');
            $table->timestamps();

            $table->foreign('user_id', 'fk_rate_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_rates');
        Schema::table('item_rates', function (Blueprint $table) {
           $table->dropForeign('fk_rate_user');
           $table->dropForeign('fk_rate_item');
        });
    }
}

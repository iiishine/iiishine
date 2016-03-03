<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrizeRecordTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PRIZE_RECORD', function(Blueprint $table) {
            $table->increments('ID');
            $table->string('MPHONE', 20);
            $table->boolean('AFTER_SHARE');

            $table->boolean('IS_MEMBER');

            $table->dateTime('CREATED_AT');
            $table->dateTime('UPDATED_AT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('PRIZE_RECORD');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSMSLOGTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SMS_LOG', function(Blueprint $table) {
            $table->increments('ID');
            $table->string('MOBILE', 30);
            $table->string('CLIENT_IP', 20);
            $table->string('CONTENT');
            $table->string('VERIFY_CODE', 10);
            $table->boolean('SENT')->default(true);
            $table->datetime('CREATED_AT');
            $table->datetime('UPDATED_AT');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('SMS_LOG');
    }

}

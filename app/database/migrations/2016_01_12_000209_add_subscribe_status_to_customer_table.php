<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscribeStatusToCustomerTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('CUSTOMERS', function(Blueprint $table) {
            $table->string('SUBSCRIBE_STATUS', 20)->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('CUSTOMERS', function(Blueprint $table) {
            $table->dropColumn('SUBSCRIBE_STATUS');
        });
    }

}

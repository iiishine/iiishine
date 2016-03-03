<?php

use Illuminate\Database\Migrations\Migration;

class FiralabsFiradminCreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function ($table)
		{
			$table->increments('id');
			$table->string('username');
			$table->string('email');
			$table->string('password');
            $table->string('remember_token', 80)->nullable();
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
		Schema::drop('users');
	}

}
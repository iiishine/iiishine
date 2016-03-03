<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('posts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('type')->nullable();
            $table->string('slug')->nullable();
            $table->integer('user_id');
            $table->text('summary');
            $table->text('content');
            $table->integer('cate_id')->default(0);

            // 内容状态：
            // 0 - 未发布
            // 1 - 已发布
            // 2 - 归档
            $table->tinyInteger('status')->default(0);

            // 内容置顶
            $table->tinyInteger('sticky')->default(0);

            $table->timestamps();

            $table->index('type');
            $table->unique('slug');
            $table->index('user_id');
            $table->index('cate_id');
            $table->index('status');
            $table->index('sticky');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('posts');
	}

}

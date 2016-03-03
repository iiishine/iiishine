<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CUSTOMERS', function(Blueprint $table) {
            $table->increments('ID');
            $table->string('MPHONE', 20)->nullable();               // 手机号码
            $table->string('SER_OPENID', 40)->nullable();           // ser openid
            $table->string('MEMBER_ID', 20)->nullable();            // 卡友member id
            $table->tinyInteger('USER_TYPE')->nullable();       // 用户身份

            $table->dateTime('NEW_MEMBER')->nullable();     // 通过活动成为新卡友的时间

            $table->boolean('VALIDCARD')->default(false);

            // 卡友进入活动获奖状态
            // 0: 未获取
            // 1: 已获取
            $table->boolean('MEMBER_PRIZE')->default(false);

            // 卡友分享获奖状态
            // 0: 未获取
            // 1: 已获取
            $table->boolean('MEMBER_SHARE')->default(false);

            // 非卡友进入活动状态
            // 0：未进入活动
            // 1：已进入活动
            $table->boolean('GUEST_PRIZE')->default(false);

            // 非卡友是否分享过
            // 0: 未分享
            // 1: 已分享
            $table->boolean('GUEST_SHARE')->default(false);

            $table->dateTime('TIME_TO_USER')->nullable();
            $table->dateTime('TIME_TO_MEMBER')->nullable();

            $table->dateTime('CREATED_AT');
            $table->dateTime('UPDATED_AT');

            $table->unique('MPHONE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('CUSTOMERS');
    }

}

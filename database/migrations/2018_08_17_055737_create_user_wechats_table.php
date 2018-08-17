<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateUserWechatsTable extends Migration
{
    const TABLE = 'user_wechats';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->comment('用户id id@users');
            $table->string('open_id')->unique()->comment('用户open_id');
            $table->string('nickname')->nullable()->comment('用户昵称');
            $table->tinyInteger('sex')->default(0)->comment('性别：1男，2女，0未知');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('country')->nullable()->comment('国家');
            $table->string('headimgurl')->nullable()->comment('头像');
            $table->string('privilege')->nullable()->comment('用户特权');
            $table->string('unique_id')->nullable()->comment('用户唯一ID');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `'. self::TABLE .'` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        DB::statement('ALTER TABLE `' . self::TABLE . '` COMMENT="用户微信表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE);
    }
}

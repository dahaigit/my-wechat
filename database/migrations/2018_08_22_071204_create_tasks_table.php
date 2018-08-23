<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    const TABLE = 'tasks';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('id@users');
            $table->string('name')->comment('任务名称');
            $table->string('description')->comment('任务描述');
            $table->timestamp('task_at')->comment('任务时间');
            $table->tinyInteger('status')->default(0)->comment('任务状态0未开始，2已完成，默认0');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `'. self::TABLE .'` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
        DB::statement('ALTER TABLE `' . self::TABLE . '` COMMENT="任务表"');
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

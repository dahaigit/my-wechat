<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTable extends Migration
{
    const TABLE = 'todos';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('status')->default(0);
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

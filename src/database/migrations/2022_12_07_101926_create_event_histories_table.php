<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_histories', function (Blueprint $table) {
            $table->id()->autoIncrement();

            $table->foreignId('play_history_id')
                // 外部キー設定
                ->constrained()
                // 子テーブルを自動で更新・削除
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('dice_number');
            $table->string('event_name');
            $table->integer('event_money');

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
        Schema::dropIfExists('event_histories');
    }
}

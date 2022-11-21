<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            // イベントidは自動採番
            $table->id()->autoIncrement();
            // 外部キー設定
            $table->foreignId('course_id')
                // 制約する
                ->constrained()
                // 子テーブルを自動で更新・削除
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // イベント名は一意の値
            $table->string('name', 30)->unique();
            // お金は空白ではいけない
            $table->integer('money')->nullable();

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
        Schema::dropIfExists('events');
    }
}

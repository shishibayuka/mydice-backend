<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DiceController extends Controller
{
    //
    public function play(Request $request)
    {
        // ランダムでサイコロを返す
        $dice_number = rand(1, 6);
        // イベントテーブルから選択したコースのデータの一覧を取得する
        $events = Event::where('course_id', $request->course_id)->get();

        // すべてのイベントからランダムで1つ残す
        // $event = Event::inRandomOrder()->first();
        // rand は引数2ついる、引数1つだとエラーになる、引数はint型しか使えない
        // $event = rand($events);

        // 取得したイベント一覧からランダムでイベントを選ぶ
        $event = Event::where('course_id', $request->course_id)->inRandomOrder()->first();

        return response()->json(['dice_number' => $dice_number, 'event' => $event]);

        // 1番目にdice_number,2番目にイベントの情報が入る
        // return response()->json([$dice_number, $event]);

        // react側ではresponse.dataに{apple: 'red', peach: 'pink'}が入っている
        // response.data.appleでredが取得できる
        // return response()->json(['apple' => 'red', 'peach' => 'pink']);
    }
}

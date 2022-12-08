<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventHistoryController extends Controller
{
    //

    public function play()
    {
        // ランダムでサイコロを返す
        $dice_number = rand(1, 6);
        // イベントテーブルからコースId１のデータの一覧を取得する
        // コースID１からのみ

        // 取得したイベント一覧からランダムでイベントを選ぶ

        // 

        //イベント一覧をランダムで選ぶ
        return response()->json($dice_number);
    }
}

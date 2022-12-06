<?php

namespace App\Http\Controllers;

use App\Models\PlayHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlayHistoryController extends Controller
{
    //

    // 履歴の保存処理 保存処理はstoreと名前をつける
    public function store(Request $request)
    {
        // Product::where('id', 1)->delete();
        PlayHistory::where('user_id', Auth::id())->delete();

        $playHistory = new PlayHistory();

        // ログインしたユーザーのIDを取得して設定する処理を追加する
        $playHistory->user_id = Auth::id();
        $playHistory->course_id = $request->course_id;
        $playHistory->save();

        return response()->json($playHistory, Response::HTTP_OK);
    }

    // Requestはデータが送られて受け取る時に使う
    public function showHistory(Request $request)
    {
        // 画面に出したいのは、マス数とコース名
        // コース選択情報とコース情報をまとめて表示する
        // play_historiesテーブルで検索にはuser_idを使う
        // withの後はモデルに記入してあるものを使う
        $course = PlayHistory::with('course')->where('user_id', Auth::id())->get();

        return response()->json($course, Response::HTTP_OK);
    }
}

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
        $playHistory = new PlayHistory();

        // ログインしたユーザーのIDを取得して設定する処理を追加する
        $playHistory->user_id = Auth::id();
        $playHistory->course_id = $request->course_id;
        $playHistory->save();

        return response()->json($playHistory, Response::HTTP_OK);
    }
}

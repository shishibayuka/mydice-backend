<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Event;

class EventController extends Controller
{
    // 保存処理
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 必須、文字、最大30字、一意
            'name' => ['required', 'string', 'max:30', 'unique:events'],
            'money' => ['required', 'integer', 'max:10000', 'min:-10000'],
        ]);

        // お金100円刻み以外でのエラー表示
        // afterは、追加のバリデーションチェックとエラーメッセージの追加のために使う
        // useを使用しないと、$requestが参照できない
        $validator->after(function ($validator) use ($request) {
            // intval関数:整数に変換する関数
            $money = intval($request->money);
            if ($money % 100 != 0) {
                $validator->error()->add('money', '100円刻みで入力してください');
            }
        });

        // 検証失敗時の処理
        if ($validator->fails()) {
            // json形式として返す ※メッセージ（エラーレスポンス）をフロントに返すために必要
            // データはjson形式で基本取り扱う
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $event = new Event();

        // Todo:編集する投稿IDを取得して設定する処理を追加する
        $event->course_id = 1;
        // カラムの値を複数更新(createの代わり)
        $event->fill($request->all())->save();


        return response()->json($event, Response::HTTP_OK);
    }

    // 全データを取得して、json形式として返す ※メッセージ（エラーレスポンス）をフロントに返すために必要
    public function index()
    {
        $events = Event::all();

        return response()->json($events, Response::HTTP_OK);
    }

    // ログインしているユーザが投稿したイベントのデータを取得する
    public function getEVENTbyCourseId()
    {
        // Todo: course_idをコースIDとする
        $events = Event::where('course_id', '1')->get();

        return response()->json($events, Response::HTTP_OK);
    }
}

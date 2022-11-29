<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Event;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    // courseのデータの取得
    // /api/course/1の場合だとcourseのidが1のデータを取得してreturnする編集ページで使用する
    // /api/course/1の場合だとreturnでidが1のコースのデータを返す
    public function show(Int $id)
    {
        $course = Course::find($id);
        return response()->json($course);
    }


    // コースの削除
    public function delete(Int $id)
    {
        // コースを取得する
        $course = Course::find($id);

        // コースが存在していたら削除処理をする
        if ($course) {
            // マイグレーションファイルに子テーブルを自動で削除
            // ->onDelete('cascade'); の設定をしているのでコースを削除するとそれに紐づくイベントも削除される
            // $courseWithEventsDelete = Course::find($id)->delete();
            $course->delete();

            return response()->json(["message" => "コースを削除しました"],  Response::HTTP_OK);
        } else {
            // コースが存在していなかったらエラーメッセージを送る
            return response()->json(["message" => "コースが見つかりませんでした"], Response::HTTP_NOT_FOUND);
        }
    }

    // コースの編集
    public function update(Int $id, Request $request)
    {
    }


    // コースとイベントの編集
    public function updateCourseAndEvent(Int $id, Request $request)
    {
        $updateCourse = $request->course;
        $course = Course::find($id);
        $course->update([
            'name' => $updateCourse["name"],
            'description' => $updateCourse["description"],
            'grid' => $updateCourse["grid"],
        ]);

        // // Todo: イベント更新処理
        // Log::debug($request->id);

        // $updateEvents = $request->events;
        // foreach ($updateEvents as $updateEvent) {
        //     $events = Event::find($request->id);

        //     $events->name = $updateEvent["name"];
        //     $events->money = $updateEvent["money"];
        //     $events->save();
        // }

        return response()->json($course);
    }


    // 一覧はindex
    public function index()
    {
        $courses = Course::all();

        return response()->json($courses, Response::HTTP_OK);
    }


    // ログインしているユーザーが投稿したコースのデータを取得する
    public function getCoursesByUserId()
    {
        $id = Auth::id();
        $courses = Course::where('user_id', $id)->get();

        return response()->json($courses, Response::HTTP_OK);
    }


    //コースのみの保存処理 保存処理はstoreと名前をつける
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:30', 'unique:courses'],
            'description' => ['string', 'max:50'],
            'grid' => ['required', 'integer', 'max:50'],
        ]);

        // マス数10刻み以外のエラー表示
        // afterは、追加のバリデーションチェックとエラーメッセージの追加のために使う
        // useを使用しないと、$requestが参照できない
        $validator->after(function ($validator) use ($request) {
            $grid = intval($request->grid);
            if ($grid % 10 != 0) {
                $validator->errors()->add('grid', '10マス刻みで入力してください');
            }
        });

        // 検証失敗時の処理
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $course = new Course();

        // ログインしたユーザーのIDを取得して設定する処理を追加する
        $course->user_id = Auth::id();
        $course->fill($request->all())->save();

        // Todo:イベントの登録処理
        // $event = new Event();

        // // Todo:編集する投稿IDを取得して設定する処理を追加する
        // $event->course_id = 1;
        // // カラムの値を複数更新(createの代わり)
        // $event->fill($request->all())->save();

        // createのやり方の参考
        // $course = Course::create([
        //     // 仮）request
        //     'user_id' => 1,
        //     // 'user_id' => $request->user_id,
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'grid' => $request->grid,
        // ]);

        return response()->json($course, Response::HTTP_OK);
    }


    // コースとイベントの表示（今回は使用しない）
    public function showCourseAndEvent(Int $id)
    {
        // コースとコースにひもづくイベントのデータを取得する（コースIDに応じて）
        $courseWithEvents = Course::with('events')->find($id);
        return response()->json($courseWithEvents, Response::HTTP_OK);

        // コースとイベントのデータを別々に取得するとき
        // $course = Course::find($id);
        // $events = $course->events;
        // return response()->json(['course' => $course, 'event' => $events], Response::HTTP_OK);
    }

    //保存処理
    // コースとイベント同時登録用保存処理
    //（今回はコースのみを保存するため、参考として残し、使用しない）
    public function create(Request $request)
    {
        // トランザクションの開始
        DB::beginTransaction();
        try {
            // 保存処理
            // コースの保存処理
            $requestCourse = $request->course;

            $course = Course::create([
                // Todo：user_idをログインユーザーに書き換える
                'user_id' => 1,
                'name' => $requestCourse["name"],
                'description' => $requestCourse["description"],
                'grid' => $requestCourse["grid"],
            ]);

            // イベントの登録処理
            $requestEvents = $request->events;

            foreach ($requestEvents as $requestEvent) {
                $event = new Event();
                // オブジェクトの場合のプロパティアクセス方法
                // $event->course_id = $course["id"];
                $event->course_id = $course->id;
                $event->name = $requestEvent["name"];
                $event->money = $requestEvent["money"];
                $event->save();
            }

            // トランザクションをコミット（処理の完了）
            DB::commit();

            // 201 Created は新規登録で使う
            return response()->json($course, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            // トランザクションをロールバック（異常発生時に途中で処理を終了）
            DB::rollBack();
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

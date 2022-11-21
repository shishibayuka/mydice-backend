<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Event;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    //保存処理
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
            return response()->json($course, Response::HTTP_OK);
        } catch (Exception $exception) {
            // トランザクションをロールバック（異常発生時に途中で処理を終了）
            DB::rollBack();
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        // Todo: user_idをログイン者のidとする
        $courses = Course::where('user_id', '1')->get();

        return response()->json($courses, Response::HTTP_OK);
    }

    //コースのみの保存処理（今回はイベントと同時保存するため、参考として残し、使用しない）
    public function createOnlyCourse(Request $request)
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

        // Todo:ログインしたユーザーのIDを取得して設定する処理を追加する
        $course->user_id = 1;
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
}

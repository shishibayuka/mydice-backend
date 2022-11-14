<?php

namespace App\Http\Controllers\API;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    //保存処理
    public function create(Request $request)
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
}

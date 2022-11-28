<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 認証処理
    // https://zenn.dev/moroshi/articles/b89bfc9c3a2976 記事を参考
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt 認証をチェックする
        if (Auth::attempt($credentials)) {
            // $request->user()にユーザーの情報が入っている
            // tokenを作成している 
            // 作成したtokenでログインを認証している
            $token = $request->user()->createToken('token-name');
            return response()->json(['api_token' => $token->plainTextToken, 'user_name' => $request->user()->name], 200);
        }
        return response()->json(['api_token' => null], 401);
    }
    //
}

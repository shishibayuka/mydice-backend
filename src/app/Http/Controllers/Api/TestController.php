<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function display(Request $request)
    {
        return response()->json(['コース' => $request->course, 'イベント' => $request->event], 200);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // 親キーcourse_idを含める場合
    // protected $fillable = ['course_id', 'name', 'money'];

    // DB上で書き換えられる値$fillable（対：guarded）
    protected $fillable = ['name', 'money'];

    // 従テーブル（event）から主テーブル（course）の情報を引き出す
    public function course()
    {
        return $this->belongTo(Course::class);
    }
}

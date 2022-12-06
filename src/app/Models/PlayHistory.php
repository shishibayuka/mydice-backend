<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayHistory extends Model
{
    use HasFactory;

    // DB上で書き換えられる値$fillable（対：guarded、絶対書き換えられない）
    protected $fillable = ['user_id', 'course_id'];

    // 従テーブルから主テーブル(user)の情報を引き出す
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 従テーブルから主テーブル(course)の情報を引き出す
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

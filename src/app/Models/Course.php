<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // requestにuser_idを含める場合
    // DB上からデータの書き換えが可能
    protected $fillable = ['user_id', 'name', 'description', 'grid'];

    // 従テーブル(courses)から主テーブル(users)の情報を引き出す
    public function user()
    {
        return $this->belongsTo(User::class);
        // return $this->belongsTo('App\Models\User');
    }

    // 主テーブル（courses）から従テーブル（events）の情報を複数引き出す
    // 「１対多」の「多」側→メソッド名は複数形
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // 主テーブル（courses）から従テーブル（events）の情報を複数引き出す
    // 「１対多」の「多」側→メソッド名は複数形
    public function play_histories()
    {
        return $this->hasMany(PlayHistory::class);
    }
}

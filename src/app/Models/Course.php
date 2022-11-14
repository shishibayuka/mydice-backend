<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // requestにuser_idを含める場合
    // protected $fillable = ['user_id', 'name', 'description', 'grid'];
    protected $fillable = ['name', 'description', 'grid'];

    // 従テーブル(courses)から主テーブル(users)の情報を複数引き出す
    public function user()
    {
        return $this->belongsTo(User::class);
        // return $this->belongsTo('App\Models\User');
    }
}

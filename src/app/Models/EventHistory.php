<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventHistory extends Model
{
    use HasFactory;


    // DB上からデータの書き換えが可能
    protected $fillable = ['play_history_id', 'dice_number', 'event_name', 'event_money'];

    // 従テーブルから主テーブル(course)の情報を引き出す
    public function play_history()
    {
        return $this->belongsTo(PlayHistory::class);
    }
}

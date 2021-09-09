<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizDate extends Model
{
    protected $table = "quiz_date";
    public $incrementing = false;
    // protected $dates = [
    //     'date',
    // ];
    protected $casts = [
        'date' => 'datetime:m/d',
    ];

    protected $fillable = ["is_deleted"];

    // public function getShowDateAttribute() {
    //     return $this->date->format('Y-m');
    // }
}

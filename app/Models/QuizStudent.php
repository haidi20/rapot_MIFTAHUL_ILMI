<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizStudent extends Model
{
    protected $table = "quiz_student";
    protected $primaryKey = "id";
    public $incrementing = false;
}

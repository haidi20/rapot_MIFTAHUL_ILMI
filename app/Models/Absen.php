<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Absen extends Model
{
    protected $table = "absen";
    public $incrementing = false;
    protected $fillable = ['student_id', 'quiz_student_id', 'date_absen_id', 'absen_type_id'];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{
    protected $table = "log_error";
    // public $incrementing = false;
    protected $primaryKey = "id";
    // protected $guarded = ["*"];
}

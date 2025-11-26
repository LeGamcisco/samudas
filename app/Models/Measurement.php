<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $table = "measurements";

    protected $fillable = [
        "parameter_id",
        "unit_id",
        "measured",
        "corrected",
        "time_group",
        "created_at",
    ];
}

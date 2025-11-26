<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorValueRca extends Model
{
    use HasFactory;
    protected $table = "sensor_value_rca";
    protected $fillable = [
        "sensor_id",
        "measured",
        "corrected",
        "raw",
    ];
}

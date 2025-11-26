<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorValueRcaLog extends Model
{
    use HasFactory;
    protected $table = "sensor_value_rca_logs";
    protected $fillable = [
        "sensor_id",
        "corrected",
        "measured",
        "raw",
    ];

}

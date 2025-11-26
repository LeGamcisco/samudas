<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SensorValueLog extends Model
{
    use HasFactory;
    protected $table = "sensor_value_logs";
    protected $fillable = [
        "sensor_id",
        "measured",
        "raw",
    ];
    public function sensor(){
        return $this->belongsTo(Sensor::class,"sensor_id");
    }
}

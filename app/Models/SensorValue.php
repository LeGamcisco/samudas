<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SensorValue extends Model
{
    use HasFactory;
    protected $table = "sensor_values";
    protected $fillable = [
        "sensor_id",
        "measured",
        "raw",
        "is_averaged",
    ];
    public function sensor(){
        return $this->belongsTo(Sensor::class,"sensor_id");
    }
}

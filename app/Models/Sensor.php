<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "sensors";
    protected $fillable = [
        "code",
        "name",
        "stack_id",
        "parameter_id",
        "unit_id",
        "analyzer_ip",
        "port",
        "extra_parameter",
        "is_has_reference",
        "o2_correction",
        "is_show",
        "is_multi_parameter",
        "formula",
    ];
    public function stack(){
        return $this->belongsTo(Stack::class, "stack_id", "id");
    }
    public function unit(){
        return $this->belongsTo(Unit::class, "unit_id", "id");
    }
    public function value(){
        return $this->belongsTo(SensorValueLog::class, "id", "sensor_id");
    }
    public function value_rca(){
        return $this->belongsTo(SensorValueRcaLog::class, "id", "sensor_id");
    }
}

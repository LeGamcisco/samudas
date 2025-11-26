<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DasLog extends Model
{
    use HasFactory;
    protected $table = "das_logs";
    protected $fillable = [
        "parameter_id",
        "unit_id",
        "measured",
        "raw",
        "is_sent",
        "time_group",
        "measured_at",
    ];
    public function parameter(){
        return $this->belongsTo(Sensor::class,"parameter_id","parameter_id");
    }
    public function unit(){
        return $this->belongsTo(Unit::class, "unit_id");
    }
}

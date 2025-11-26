<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reference extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "references";
    protected $fillable = [
        "sensor_id",
        "range_start",
        "range_end",
        "formula",
    ];
    public function sensor(){
        return $this->belongsTo(Sensor::class,"sensor_id");
    }
}

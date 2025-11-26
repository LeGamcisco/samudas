<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuration extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "configurations";
    protected $fillable = [
        "name",
        "server_ip",
        "server_url",
        "server_apikey",
        "day_backup",
        "is_rca",
        "rca_stack",
    ];
}

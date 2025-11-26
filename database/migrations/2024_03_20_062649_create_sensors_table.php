<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->string("name")->nullable(); // Sensor name
            $table->integer("stack_id")->nullable();
            $table->integer("parameter_id")->nullable();
            $table->integer("unit_id")->nullable();
            $table->string("analyzer_ip")->nullable(); // IP or /dev/ttyUSB
            $table->string("port")->nullable(); // PORT or AIN
            $table->integer("extra_parameter")->nullable();
            $table->integer("o2_correction")->nullable();
            $table->integer("is_show")->nullable();
            $table->integer("is_multi_parameter")->nullable();
            $table->text("formula")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};

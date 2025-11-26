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
        Schema::create('das_logs', function (Blueprint $table) {
            $table->id();
            $table->integer("parameter_id")->nullable()->index();
            $table->integer("unit_id")->nullable()->index();
            $table->double("measured")->nullable();
            $table->double("raw")->nullable();
            $table->integer("is_sent")->default(0)->nullable();
            $table->timestamp("time_group")->nullable();
            $table->timestamp("measured_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('das_logs');
    }
};

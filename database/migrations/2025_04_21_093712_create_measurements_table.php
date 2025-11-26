<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\IndexHint;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->integer("parameter_id")->index();
            $table->integer("unit_id")->index();
            $table->double("measured")->default(0);
            $table->double("corrected")->default(0);
            $table->timestamp("time_group")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};

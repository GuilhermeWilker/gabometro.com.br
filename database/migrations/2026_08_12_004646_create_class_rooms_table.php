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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('grade_level')->nullable(); // ex: "2º Ensino Médio"
            $table->string('section'); // ex: "A", "B", "C"
            $table->string('academic_year', 4)->nullable(); // ex: "2026"
            $table->timestamps();

            $table->unique(['grade_level', 'section', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};

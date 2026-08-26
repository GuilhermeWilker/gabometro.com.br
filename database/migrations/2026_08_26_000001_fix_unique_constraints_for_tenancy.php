<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
      {

            Schema::table('class_rooms', function (Blueprint $table) {
                  $table->dropUnique(['grade_level', 'section', 'academic_year']);
                  $table->unique(['school_id', 'grade_level', 'section', 'academic_year']);
            });

            Schema::table('students', function (Blueprint $table) {
                  $table->dropUnique(['registration_number']);
                  $table->unique(['school_id', 'registration_number']);
            });

            Schema::table('subjects', function (Blueprint $table) {
                  $table->dropUnique(['abbreviation']);
                  $table->unique(['school_id', 'abbreviation']);
            });
      }

      public function down(): void
      {
            Schema::table('class_rooms', function (Blueprint $table) {
                  $table->dropUnique(['school_id', 'grade_level', 'section', 'academic_year']);
                  $table->unique(['grade_level', 'section', 'academic_year']);
            });

            Schema::table('students', function (Blueprint $table) {
                  $table->dropUnique(['school_id', 'registration_number']);
                  $table->unique(['registration_number']);
            });

            Schema::table('subjects', function (Blueprint $table) {
                  $table->dropUnique(['school_id', 'abbreviation']);
                  $table->unique(['abbreviation']);
            });
      }
};

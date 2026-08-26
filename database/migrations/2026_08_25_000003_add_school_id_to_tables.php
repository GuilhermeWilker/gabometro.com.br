<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
      {
            Schema::table('class_rooms', function (Blueprint $table) {
                  $table->foreignId('school_id')->after('id')->constrained()->cascadeOnDelete();
            });

            Schema::table('students', function (Blueprint $table) {
                  $table->foreignId('school_id')->after('id')->constrained()->cascadeOnDelete();
            });

            Schema::table('assessments', function (Blueprint $table) {
                  $table->foreignId('school_id')->after('id')->constrained()->cascadeOnDelete();
            });

            Schema::table('subjects', function (Blueprint $table) {
                  $table->foreignId('school_id')->after('id')->constrained()->cascadeOnDelete();
            });
      }

      public function down(): void
      {
            Schema::table('class_rooms', function (Blueprint $table) {
                  $table->dropConstrainedForeignId('school_id');
            });

            Schema::table('students', function (Blueprint $table) {
                  $table->dropConstrainedForeignId('school_id');
            });

            Schema::table('assessments', function (Blueprint $table) {
                  $table->dropConstrainedForeignId('school_id');
            });

            Schema::table('subjects', function (Blueprint $table) {
                  $table->dropConstrainedForeignId('school_id');
            });
      }
};

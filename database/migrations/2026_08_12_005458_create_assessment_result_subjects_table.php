<?php

use App\Models\AssessmentResult;
use App\Models\Subject;
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
        Schema::create('assessment_result_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AssessmentResult::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Subject::class)->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('correct_answers')->default(0);
            $table->timestamps();
            $table->unique(['assessment_result_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_result_subjects');
    }
};

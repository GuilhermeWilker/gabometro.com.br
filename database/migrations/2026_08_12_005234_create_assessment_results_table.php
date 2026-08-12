<?php

use App\Models\Assessment;
use App\Models\Students;
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
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Assessment::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Students::class)->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('correct_answers')->default(0);
            $table->unsignedSmallInteger('incorrect_answers')->default(0);
            $table->unsignedSmallInteger('total_questions')->default(0);
            $table->string('pdf_path')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();

            $table->unique(['assessment_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};

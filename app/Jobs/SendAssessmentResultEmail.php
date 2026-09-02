<?php

namespace App\Jobs;

use App\Mail\AssessmentResultMail;
use App\Models\AssessmentResult;
use App\Services\SchoolMailConfigurator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendAssessmentResultEmail implements ShouldQueue
{
      use Queueable;

      public function __construct(public int $resultId) {}

      public function handle(): void
      {
            $result = AssessmentResult::with(['student', 'assessment.school'])->findOrFail($this->resultId);
            $school = $result->assessment->school;
            $student = $result->student;

            $to = $student->email ?: $student->guardian_email;

            if (blank($to)) {
                  return; // sem e-mail
            }

            if (! $result->pdf_path) {
                  app(\App\Services\AssessmentResultPdfService::class)->generate($result);
                  $result->refresh();
            }

            $result->assessment->status = 'enviado';

            SchoolMailConfigurator::apply($school);

            Mail::to($to)->send(new AssessmentResultMail($result));

            $result->update(['email_sent_at' => now()]);
      }
}

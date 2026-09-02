<?php

namespace App\Jobs;

use App\Models\AssessmentResult;
use App\Services\AssessmentResultPdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateAssessmentResultPdf implements ShouldQueue
{
      use Queueable;

      public function __construct(public int $resultId) {}

      public function handle(AssessmentResultPdfService $service): void
      {
            $result = AssessmentResult::findOrFail($this->resultId);
            $service->generate($result);
      }
}

<?php

namespace App\Jobs;

use App\Imports\AssessmentResultsImport;
use App\Models\Assessment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ImportAssessmentResults implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $assessment_id
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $assessment = Assessment::findOrFail($this->assessment_id);

        Excel::import(
            new AssessmentResultsImport($assessment),
            $assessment->spreadsheet_path,
            'local'
        );

        $assessment->update(['status' => 'processed']);
    }
}

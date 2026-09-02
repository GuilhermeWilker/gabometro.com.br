<?php

namespace App\Services;

use App\Models\AssessmentResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssessmentResultPdfService
{
    public function generate(AssessmentResult $result): string
    {
        $result->loadMissing([
            'student.classRoom',
            'assessment.school',
            'subjects',
        ]);

        $school = $result->assessment->school;
        $student = $result->student;
        $assessment = $result->assessment;

        $percentage = $result->total_questions > 0
            ? round(($result->correct_answers / $result->total_questions) * 100, 1)
            : 0;

        $pdf = Pdf::loadView('pdfs.assessment-result', [
            'school' => $school,
            'student' => $student,
            'assessment' => $assessment,
            'result' => $result,
            'percentage' => $percentage,
            'subjects' => $result->subjects,
        ])->setPaper('a4');

        $filename = sprintf(
            'assessments/%s/%s-%s.pdf',
            $assessment->id,
            Str::slug($student->registration_number),
            Str::slug($student->name)
        );

        Storage::disk('local')->put($filename, $pdf->output());

        $result->update(['pdf_path' => $filename]);

        return $filename;
    }
}

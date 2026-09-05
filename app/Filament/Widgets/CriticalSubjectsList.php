<?php

namespace App\Filament\Widgets;

use App\Models\Subject;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class CriticalSubjectsList extends Widget
{
      protected string $view = 'filament.widgets.critical-subjects-list';

      protected int | string | array $columnSpan = 1;

      protected ?string $pollingInterval = null;

      public function getSubjects(): array
      {
            $schoolId = Filament::getTenant()?->id;

            if (! $schoolId) {
                  return [];
            }

            return Subject::query()
                  ->where('subjects.school_id', $schoolId)
                  ->leftJoin('assessment_result_subject', 'subjects.id', '=', 'assessment_result_subject.subject_id')
                  ->leftJoin('assessment_results', 'assessment_result_subject.assessment_result_id', '=', 'assessment_results.id')
                  ->leftJoin('assessments', 'assessment_results.assessment_id', '=', 'assessments.id')
                  ->where(function ($q) use ($schoolId) {
                        $q->where('assessments.school_id', $schoolId)
                              ->orWhereNull('assessments.id');
                  })
                  ->groupBy('subjects.id', 'subjects.abbreviation', 'subjects.name')
                  ->select([
                        'subjects.abbreviation',
                        'subjects.name',
                        DB::raw('ROUND(AVG(assessment_result_subject.correct_answers), 1) as avg_correct'),
                  ])
                  ->orderBy('avg_correct')
                  ->limit(5)
                  ->get()
                  ->map(fn($s) => [
                        'label' => $s->abbreviation,
                        'name' => $s->name,
                        'avg' => (float) ($s->avg_correct ?? 0),
                  ])
                  ->toArray();
      }
}

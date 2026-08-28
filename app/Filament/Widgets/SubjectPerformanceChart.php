<?php

namespace App\Filament\Widgets;

use App\Models\Subject;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SubjectPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Desempenho por disciplina';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $schoolId = Filament::getTenant()?->id;

        if (! $schoolId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $data = Subject::query()
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
            ->orderBy('subjects.abbreviation')
            ->get();

        $palette = [
            '#6366f1', // indigo
            '#22c55e', // green
            '#f59e0b', // amber
            '#ef4444', // red
            '#06b6d4', // cyan
            '#a855f7', // purple
            '#ec4899', // pink
            '#84cc16', // lime
            '#f97316', // orange
            '#14b8a6', // teal
        ];

        $colors = $data->keys()->map(
            fn($index) => $palette[$index % count($palette)]
        )->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Média de acertos',
                    'data' => $data->pluck('avg_correct')->map(fn($v) => $v ?? 0)->toArray(),
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->map(
                fn($s) => $s->name ? "{$s->abbreviation} ({$s->name})" : $s->abbreviation
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

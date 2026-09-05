<?php

namespace App\Filament\Widgets;

use App\Models\Subject;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class SubjectPerformanceChart extends ApexChartWidget
{
    protected static ?string $chartId = 'subjectPerformanceChart';

    protected static ?string $heading = 'Desempenho por disciplina';

    protected static ?int $sort = 3;

    protected static ?int $contentHeight = 320;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected ?string $pollingInterval = null;

    protected function getOptions(): array
    {
        $schoolId = Filament::getTenant()?->id;

        if (! $schoolId) {
            return $this->emptyOptions();
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

        $labels = $data->map(
            fn($s) => $s->name ? "{$s->abbreviation}" : $s->abbreviation
        )->toArray();

        $series = $data->pluck('avg_correct')->map(fn($v) => (float) ($v ?? 0))->toArray();

        // Nord-friendly
        $colors = [
            '#263ff3', // primary (base)
            '#4c63f5', // primary light
            '#1a2fd1', // primary dark
            '#6b7ef7', // soft
            '#0ea5e9', // sky (complemento frio)
            '#06b6d4', // cyan
            '#6366f1', // indigo vizinho
            '#8b5cf6', // violet
            '#22c55e', // success
            '#f59e0b', // warning
            '#ef4444', // danger
            '#64748b', // muted
        ];

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 320,
                'fontFamily' => 'inherit',
                'toolbar' => ['show' => false],
            ],
            'series' => $series,
            'labels' => $labels,
            'colors' => $colors,
            'legend' => [
                'position' => 'bottom',
                'fontFamily' => 'inherit',
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
            'stroke' => [
                'width' => 0,
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '65%',
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'label' => 'Média',
                                'formatter' => null, // ver extraJs se quiser
                            ],
                        ],
                    ],
                ],
            ],
            'theme' => [
                'mode' => 'light', // o plugin respeita dark mode do Filament
            ],
        ];
    }

    private function emptyOptions(): array
    {
        return [
            'chart' => ['type' => 'donut', 'height' => 320],
            'series' => [],
            'labels' => [],
        ];
    }
}

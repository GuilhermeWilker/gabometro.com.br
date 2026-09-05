<?php

namespace App\Filament\Resources\Students\Widgets;

use App\Models\Students;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StudentEvolutionChart extends ApexChartWidget
{
    public ?Students $record = null;

    protected static ?string $chartId = 'studentEvolutionChart';

    protected static ?string $heading = 'Evolução do aluno';

    protected static ?int $contentHeight = 280;

    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 1;

    protected function getOptions(): array
    {
        $student = $this->record;

        if (! $student) {
            return $this->empty();
        }

        $results = $student->results()
            ->with('assessment')
            ->get()
            ->sortBy(fn($r) => $r->assessment->assessment_date);

        $labels = $results->map(fn($r) => $r->assessment->name)->values()->toArray();

        $data = $results->map(function ($result) {
            if ($result->total_questions === 0) {
                return 0;
            }

            return round(($result->correct_answers / $result->total_questions) * 100, 1);
        })->values()->toArray();

        return [
            'chart' => [
                'type' => 'area',
                'height' => 280,
                'fontFamily' => 'inherit',
                'toolbar' => ['show' => false],
                'zoom' => ['enabled' => false],
            ],
            'series' => [
                [
                    'name' => 'Aproveitamento (%)',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'style' => ['fontFamily' => 'inherit', 'fontWeight' => 600],
                ],
            ],
            'yaxis' => [
                'min' => 0,
                'max' => 100,
                'labels' => [
                    'style' => ['fontFamily' => 'inherit'],
                ],
            ],
            'colors' => ['#263ff3'],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 3,
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.45,
                    'opacityTo' => 0.05,
                ],
            ],
            'markers' => [
                'size' => 4,
            ],
            'dataLabels' => ['enabled' => false],
            'grid' => [
                'strokeDashArray' => 4,
            ],
        ];
    }

    private function empty(): array
    {
        return [
            'chart' => ['type' => 'area', 'height' => 280],
            'series' => [['name' => 'Aproveitamento (%)', 'data' => []]],
            'xaxis' => ['categories' => []],
        ];
    }
}

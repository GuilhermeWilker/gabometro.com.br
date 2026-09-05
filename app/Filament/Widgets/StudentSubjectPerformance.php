<?php

namespace App\Filament\Resources\Students\Widgets;

use App\Models\Students;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StudentSubjectPerformance extends ApexChartWidget
{
    public ?Students $record = null;

    protected static ?string $chartId = 'studentSubjectPerformance';

    protected static ?string $heading = 'Desempenho por matéria';

    protected static ?int $contentHeight = 280;
    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 1;

    protected function getOptions(): array
    {
        $student = $this->record;

        if (! $student) {
            return $this->empty();
        }

        $latest = $student->results()
            ->with(['assessment', 'subjects'])
            ->get()
            ->sortByDesc(fn($r) => $r->assessment->assessment_date)
            ->first();

        if (! $latest) {
            return $this->empty();
        }

        $subjects = $latest->subjects;
        $categories = $subjects->pluck('abbreviation')->values()->toArray();
        $data = $subjects->map(fn($s) => (int) $s->pivot->correct_answers)->values()->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 280,
                'fontFamily' => 'inherit',
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Acertos',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => [
                    'style' => ['fontFamily' => 'inherit', 'fontWeight' => 600],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => ['fontFamily' => 'inherit'],
                ],
            ],
            'colors' => ['#263ff3'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 6,
                    'columnWidth' => '55%',
                    'distributed' => true, // cor por barra (opcional)
                ],
            ],
            'dataLabels' => ['enabled' => false],
            'grid' => [
                'strokeDashArray' => 4,
            ],
            'legend' => ['show' => false],
        ];
    }

    private function empty(): array
    {
        return [
            'chart' => ['type' => 'bar', 'height' => 280],
            'series' => [['name' => 'Acertos', 'data' => []]],
            'xaxis' => ['categories' => []],
        ];
    }
}

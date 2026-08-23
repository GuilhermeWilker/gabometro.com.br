<?php

namespace App\Filament\Resources\Students\Widgets;

use App\Models\Students;
use Filament\Widgets\ChartWidget;

class StudentSubjectPerformance extends ChartWidget
{
    public ?Students $record = null;

    protected ?string $heading = 'Desempenho por matéria';

    protected ?string $maxHeight = '280px';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $student = $this->record;

        if (! $student) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $latest = $student->results()
            ->with('assessment', 'subjects')
            ->get()
            ->sortByDesc(
                fn($result) => $result->assessment->assessment_date
            )
            ->first();

        if (! $latest) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $subjects = $latest->subjects;

        return [
            'datasets' => [
                [
                    'label' => 'Aproveitamento',
                    'data' => $subjects
                        ->map(fn($subject) => (int) $subject->pivot->correct_answers)
                        ->values()
                        ->toArray(),
                ],
            ],

            'labels' => $subjects
                ->pluck('abbrevitation')
                ->values()
                ->toArray(),
        ];
    }
}

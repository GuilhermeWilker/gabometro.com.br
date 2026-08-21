<?php

namespace App\Filament\Resources\Students\Widgets;

use App\Models\Students;
use Filament\Widgets\ChartWidget;

class StudentEvolutionChart extends ChartWidget
{
    public ?Students $record = null;

    protected ?string $heading = 'Evolução do aluno';

    protected ?string $maxHeight = '300px';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
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

        $results = $student->results()
            ->with('assessment')
            ->get()
            ->sortBy(
                fn($result) => $result->assessment->assessment_date
            );

        return [
            'datasets' => [
                [
                    'label' => 'Aproveitamento (%)',

                    'data' => $results
                        ->map(function ($result) {
                            if ($result->total_questions === 0) {
                                return 0;
                            }

                            return round(
                                (
                                    $result->correct_answers
                                    / $result->total_questions
                                ) * 100,
                                2
                            );
                        })
                        ->values()
                        ->toArray(),
                ],
            ],

            'labels' => $results
                ->map(
                    fn($result) =>
                    $result->assessment->name
                )
                ->values()
                ->toArray(),
        ];
    }
}

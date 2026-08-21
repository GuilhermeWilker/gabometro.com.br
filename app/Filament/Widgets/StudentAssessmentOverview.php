<?php

namespace App\Filament\Resources\Students\Widgets;

use App\Models\Students;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentAssessmentOverview extends StatsOverviewWidget
{
    public ?Students $record = null;

    protected function getStats(): array
    {
        $student = $this->record;

        if (! $student) {
            return [];
        }

        $results = $student->results()
            ->with('assessment')
            ->get();

        $latest = $results
            ->sortByDesc(
                fn($result) => $result->assessment->assessment_date
            )
            ->first();

        if (! $latest) {
            return [
                Stat::make('Avaliações', 0),
                Stat::make('Acertos', 0),
                Stat::make('Aproveitamento', '0%'),
            ];
        }

        $percentage = $latest->total_questions > 0
            ? ($latest->correct_answers / $latest->total_questions) * 100
            : 0;

        $previous = $results
            ->sortByDesc(
                fn($result) => $result->assessment->assessment_date
            )
            ->skip(1)
            ->first();

        $variation = null;

        if ($previous && $previous->total_questions > 0) {
            $previousPercentage =
                ($previous->correct_answers / $previous->total_questions) * 100;

            $variation = $percentage - $previousPercentage;
        }

        return [
            Stat::make(
                'Aproveitamento',
                number_format($percentage, 2, ',', '.') . '%'
            )
                ->description(
                    $variation !== null
                        ? ($variation >= 0 ? '+' : '') .
                        number_format($variation, 2, ',', '.') .
                        '% vs. anterior'
                        : 'Primeira avaliação'
                )
                ->descriptionIcon(
                    $variation !== null && $variation >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->color(
                    $percentage >= 80
                        ? 'success'
                        : ($percentage >= 60 ? 'warning' : 'danger')
                ),

            Stat::make(
                'Acertos',
                "{$latest->correct_answers} / {$latest->total_questions}"
            )
                ->description($latest->assessment->name)
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(
                'Erros',
                $latest->incorrect_answers
            )
                ->description('Última avaliação')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}

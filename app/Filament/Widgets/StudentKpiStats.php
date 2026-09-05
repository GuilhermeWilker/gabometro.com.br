<?php

namespace App\Filament\Resources\Students\Widgets;

use App\Models\Students;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentKpiStats extends StatsOverviewWidget
{
      public ?Students $record = null;

      protected ?string $pollingInterval = null;

      protected function getStats(): array
      {
            $student = $this->record;

            if (! $student) {
                  return [
                        Stat::make('Acertos', '—'),
                        Stat::make('Erros', '—'),
                        Stat::make('Provas', '0'),
                  ];
            }

            $results = $student->results()
                  ->with('assessment')
                  ->get();

            $latest = $results
                  ->sortByDesc(fn($result) => $result->assessment->assessment_date)
                  ->first();

            if (! $latest) {
                  return [
                        Stat::make('Acertos', '0')
                              ->description('Nenhuma prova ainda')
                              ->descriptionIcon('heroicon-m-check-circle')
                              ->color('gray'),

                        Stat::make('Erros', '0')
                              ->description('Nenhuma prova ainda')
                              ->descriptionIcon('heroicon-m-x-circle')
                              ->color('gray'),

                        Stat::make('Provas', '0')
                              ->description('Sem avaliações')
                              ->descriptionIcon('heroicon-m-document-text')
                              ->color('gray'),
                  ];
            }

            $percentage = $latest->total_questions > 0
                  ? ($latest->correct_answers / $latest->total_questions) * 100
                  : 0;

            $previous = $results
                  ->sortByDesc(fn($result) => $result->assessment->assessment_date)
                  ->skip(1)
                  ->first();

            $variation = null;

            if ($previous && $previous->total_questions > 0) {
                  $previousPercentage = ($previous->correct_answers / $previous->total_questions) * 100;
                  $variation = $percentage - $previousPercentage;
            }

            return [
                  Stat::make(
                        'Acertos',
                        "{$latest->correct_answers} / {$latest->total_questions}"
                  )
                        ->description($latest->assessment->name ?? 'Última prova')
                        ->descriptionIcon('heroicon-m-check-circle')
                        ->color('success'),

                  Stat::make('Erros', (string) $latest->incorrect_answers)
                        ->description(
                              $variation !== null
                                    ? (
                                          ($variation >= 0 ? '+' : '')
                                          . number_format($variation, 1, ',', '.')
                                          . '% vs. anterior'
                                    )
                                    : 'Última avaliação'
                        )
                        ->descriptionIcon(
                              $variation !== null && $variation < 0
                                    ? 'heroicon-m-arrow-trending-down'
                                    : 'heroicon-m-x-circle'
                        )
                        ->color('danger'),

                  Stat::make('Provas', (string) $results->count())
                        ->description(
                              $results->count() === 1
                                    ? '1 avaliação registrada'
                                    : $results->count() . ' avaliações registradas'
                        )
                        ->descriptionIcon('heroicon-m-document-text')
                        ->color('info'),
            ];
      }
}

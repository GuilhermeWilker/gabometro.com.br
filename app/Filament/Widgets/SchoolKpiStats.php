<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\ClassRoom;
use App\Models\Students;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchoolKpiStats extends StatsOverviewWidget
{
      protected ?string $pollingInterval = null;

      protected function getStats(): array
      {
            $schoolId = Filament::getTenant()?->id;

            if (! $schoolId) {
                  return [];
            }

            return [
                  Stat::make('Alunos', Students::where('school_id', $schoolId)->count())
                        ->description('Alunos ativos')
                        ->descriptionIcon('heroicon-m-academic-cap')
                        ->color('success'),

                  Stat::make('Turmas', ClassRoom::where('school_id', $schoolId)->count())
                        ->description('Turmas cadastradas')
                        ->descriptionIcon('heroicon-m-building-office-2')
                        ->color('primary'),

                  Stat::make('Avaliações', Assessment::where('school_id', $schoolId)->count())
                        ->description('Simulados registrados')
                        ->descriptionIcon('heroicon-m-clipboard-document-check')
                        ->color('warning'),
            ];
      }
}

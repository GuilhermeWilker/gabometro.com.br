<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\ClassRoom;
use App\Models\Students;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SchoolStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $schoolId = Filament::getTenant()?->id;

        if (! $schoolId) {
            return [];
        }

        $totalClassRooms = ClassRoom::where('school_id', $schoolId)->count();
        $totalStudents = Students::where('school_id', $schoolId)->count();
        $totalAssessments = Assessment::where('school_id', $schoolId)->count();

        // Média geral: (acertos / total de questões) * 100
        $avg = AssessmentResult::query()
            ->whereHas('assessment', fn($q) => $q->where('school_id', $schoolId))
            ->select(DB::raw('
                ROUND(
                    AVG(
                        CASE 
                            WHEN total_questions > 0 
                            THEN (correct_answers * 100.0 / total_questions) 
                            ELSE NULL 
                        END
                    ), 1
                ) as average
            '))
            ->value('average');

        return [
            Stat::make('Turmas', $totalClassRooms)
                ->description('Turmas cadastradas')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Alunos', $totalStudents)
                ->description('Alunos ativos')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Avaliações', $totalAssessments)
                ->description('Avaliações realizadas')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),

            Stat::make('Média geral', $avg !== null ? "{$avg}%" : '—')
                ->description('Desempenho médio da escola')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($avg >= 70 ? 'success' : ($avg >= 50 ? 'warning' : 'danger')),
        ];
    }
}

<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentsResource;
use App\Filament\Resources\Students\Widgets\StudentAssessmentOverview;
use App\Filament\Resources\Students\Widgets\StudentEvolutionChart;
use App\Filament\Resources\Students\Widgets\StudentSubjectPerformance;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudents extends ViewRecord
{
    protected static string $resource = StudentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StudentAssessmentOverview::make([
                'record' => $this->record,
            ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            StudentSubjectPerformance::make([
                'record' => $this->record,
            ]),

            StudentEvolutionChart::make([
                'record' => $this->record,
            ]),
        ];
    }
}

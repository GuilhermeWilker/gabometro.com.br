<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentsResource;
use App\Filament\Resources\Students\Widgets\StudentEvolutionChart;
use App\Filament\Resources\Students\Widgets\StudentOverallGauge;
use App\Filament\Resources\Students\Widgets\StudentSubjectPerformance;
use App\Filament\Widgets\StudentPdfsTable;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Contracts\Support\Htmlable;

class ViewStudents extends ViewRecord
{
    protected static string $resource = StudentsResource::class;

    protected string $view = 'filament.resources.students.pages.view-student';

    public function getTitle(): string | Htmlable
    {
        return 'Detalhes do estudante';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    // dados do aluno no topo da blade (compactos)
    public function getStudentMeta(): array
    {
        $s = $this->record;

        return [
            'name' => $s->name,
            'registration' => $s->registration_number,
            'class' => $s->classRoom
                ? "{$s->classRoom->grade_level} {$s->classRoom->section}"
                : '—',
            'email' => $s->email ?? '—',
        ];
    }
}

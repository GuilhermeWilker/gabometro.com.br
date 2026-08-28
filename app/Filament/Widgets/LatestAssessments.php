<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestAssessments extends TableWidget
{
    protected static ?string $heading = 'Últimas avaliações';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Assessment::query()
                    ->where('school_id', Filament::getTenant()?->id)
                    ->with('classRoom')
                    ->latest()
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Prova')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('classRoom.grade_level')
                    ->label('Turma')
                    ->formatStateUsing(
                        fn($record) => trim("{$record->classRoom?->grade_level} {$record->classRoom?->section}")
                    ),

                TextColumn::make('assessment_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'processed' => 'success',
                        'processing' => 'warning',
                        'error' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Criada em')
                    ->since()
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}

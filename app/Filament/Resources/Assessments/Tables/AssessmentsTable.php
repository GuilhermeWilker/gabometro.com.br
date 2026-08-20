<?php

namespace App\Filament\Resources\Assessments\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Support\Colors\Color;

class AssessmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('class_room_id')
                    ->label('Série e turma')
                    ->state(fn($record) => "{$record->classRoom->grade_level} {$record->classRoom->section}")
                    ->sortable(),
                TextColumn::make('assessment_date')
                    ->label('Data de aplicação')
                    ->isoDate()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->size('7rem')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'processed' => 'success',
                        'processing' => 'warning',
                        'error' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('downloadSpreadsheet')
                    ->label('Baixar planilha')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color(Color::Yellow)
                    ->visible(fn($record) => filled($record->spreadsheet_path) && Storage::disk('local')->exists($record->spreadsheet_path))
                    ->action(fn($record) => response()->download(
                        Storage::disk('local')->path($record->spreadsheet_path),
                        Str::slug($record->name) . '.xlsx'
                    )),

                // EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

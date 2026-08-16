<?php

namespace App\Filament\Resources\ClassRooms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClassRoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Série e turma')
                    ->label('Série e turma')
                    ->state(fn($record) => $record->grade_level ? "{$record->grade_level} {$record->section}" : 'N/A')
                    ->searchable(['grade_level', 'section'])
                    ->sortable(),
                TextColumn::make('students_count')
                    ->alignCenter()
                    ->label('Quantidade de Alunos')
                    ->counts('students')
                    ->size('8rem')
                    ->icon(Heroicon::UserGroup)
                    ->iconPosition('after')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('academic_year')
                    ->alignCenter()
                    ->label('Ano Acadêmico')
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('registration_number')
                    ->alignCenter()
                    ->size('7rem')
                    ->badge()
                    ->copyMessage('E-mail copiado!')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                TextColumn::make('email')
                    ->alignCenter()
                    ->size('7rem')
                    ->label('E-mail institucional')
                    ->state(
                        fn($record) =>
                        $record->email
                            ? $record->email
                            : 'N/A'
                    )->copyable()
                    ->badge()
                    ->copyMessage('E-mail copiado!')
                    ->copyMessageDuration(1500)
                    ->icon(Heroicon::Envelope),
                TextColumn::make('classRoom.grade_level')
                    ->label('Série')
                    ->state(fn($record) => $record->classRoom?->grade_level ? "{$record->classRoom?->grade_level} {$record->classRoom?->section}" : 'N/A')
                    ->searchable()
                    ->sortable(),
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
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

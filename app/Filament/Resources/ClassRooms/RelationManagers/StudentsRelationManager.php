<?php

namespace App\Filament\Resources\ClassRooms\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Matrícula')
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->size('1.7rem')
                    ->label('E-mail institucional')
                    ->state(
                        fn($record) =>
                        $record->registration_number
                            ? $record->registration_number . '@aluno.edu.br'
                            : 'N/A'
                    )->copyable()
                    ->badge()
                    ->copyMessage('E-mail copiado!')
                    ->copyMessageDuration(1500)
                    ->icon(Heroicon::Clipboard)
                    ->iconPosition('after'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

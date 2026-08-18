<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\ClassRoom;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('registration_number')
                    ->label('Número de matrícula')
                    ->placeholder('12345')
                    ->numeric()
                    ->required(),

                TextInput::make('name')
                    ->label('Nome do Estudante')
                    ->placeholder('João Silva Lima')
                    ->required(),

                Select::make('class_room_id')
                    ->label('Série')
                    ->options(
                        ClassRoom::query()
                            ->get()
                            ->mapWithKeys(fn($classRoom) => [
                                $classRoom->id => "{$classRoom->grade_level} {$classRoom->section}",
                            ])
                            ->toArray()
                    )
                    ->required(),
            ]);
    }
}

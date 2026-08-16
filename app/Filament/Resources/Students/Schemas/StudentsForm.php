<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('registration_number')
                    ->required(),
                TextInput::make('class_room_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}

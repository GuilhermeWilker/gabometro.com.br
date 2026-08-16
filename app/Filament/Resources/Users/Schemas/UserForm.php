<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('role')->options([
                    'Administrador' => 'Administrador',
                    'Coodernador' => 'Coodernador',
                    'Professor' => 'Professor',
                ])->required(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->visible(fn(string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state)),
            ]);
    }
}

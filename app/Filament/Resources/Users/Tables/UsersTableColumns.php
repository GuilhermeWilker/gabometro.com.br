<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\Layout\Stack;

class UsersTableColumns
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function make(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nome')
                ->searchable(),
            TextColumn::make('role')
                ->label('Função do usuário')
                ->badge()
                ->icon(fn(string $state): Heroicon => match ($state) {
                    'Administrador' => Heroicon::ShieldCheck,
                    'Coodernador' => Heroicon::UserGroup,
                    'Professor' => Heroicon::AcademicCap,
                    default => Heroicon::User,
                })
                ->color(fn(string $state): string => match ($state) {
                    'Administrador' => 'primary',
                    'Coodernador' => 'warning',
                    'Professor' => 'success',
                    default => 'gray',
                }),
            TextColumn::make('email')
                ->icon(Heroicon::Envelope)
                ->iconColor(Color::Blue)
                ->label('Email')
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Cadastro do usuário')
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}

<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nome do estudante')
                            ->weight('bold'),

                        TextEntry::make('registration_number')
                            ->label('Matrícula'),

                        TextEntry::make('classRoom')
                            ->label('Turma')
                            ->formatStateUsing(
                                fn($state) => $state
                                    ? "{$state->grade_level} {$state->section}"
                                    : '-'
                            ),

                        TextEntry::make('created_at')
                            ->label('Cadastro')
                            ->date('d/m/Y')
                            ->placeholder('-'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 4,
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

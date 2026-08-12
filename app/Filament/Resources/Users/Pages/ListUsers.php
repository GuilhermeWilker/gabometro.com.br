<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo Usuário')
                ->icon(Heroicon::UserPlus)
                ->schema([
                    Wizard::make([
                        Step::make('Informações Gerais')
                            ->description('Preencha as informações do usuário')
                            ->icon(Heroicon::User)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nome do Usuário')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),
                                TextInput::make('password')
                                    ->label('Senha')
                                    ->password()
                                    ->required(),
                            ]),
                        Step::make('Função do Usuário')

                            ->description('Selecione o papel do usuário')
                            ->icon(Heroicon::PuzzlePiece)
                            ->schema([
                                Select::make('role')->options([
                                    'admin' => 'Administrator',
                                    'coordinator' => 'Coordinator',
                                    'teacher' => 'Teacher',
                                ])->required()
                            ])
                    ]),
                ]),
        ];
    }
}

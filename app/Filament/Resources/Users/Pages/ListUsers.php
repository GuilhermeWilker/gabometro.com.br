<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

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
                                    ->required()
                                    ->unique(User::class),
                                TextInput::make('password')
                                    ->label('Senha')
                                    ->password()
                                    ->required()
                                    ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                            ]),
                        Step::make('Função do Usuário')
                            ->description('Selecione o papel do usuário')
                            ->icon(Heroicon::PuzzlePiece)
                            ->schema([
                                Select::make('role')
                                    ->label('Função')
                                    ->options([
                                        'Administrador' => 'Administrador',
                                        'Coodernador' => 'Coordenador', // mantém o valor do banco (com o typo atual)
                                        'Professor' => 'Professor',
                                    ])
                                    ->required(),
                            ]),
                    ]),
                ])
                ->using(function (array $data): User {
                    $user = User::create($data);

                    // vincula o usuário à escola atual
                    $user->schools()->attach(Filament::getTenant()->id);

                    return $user;
                }),
        ];
    }
}

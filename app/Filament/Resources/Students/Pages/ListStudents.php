<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentsResource;
use App\Models\ClassRoom;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Get as UtilitiesGet;
use Filament\Schemas\Components\Utilities\Set as UtilitiesSet;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::Plus)
                ->label("Adicionar um estudante")
                ->schema([
                    Wizard::make([
                        Step::make('Informações Gerais')
                            ->description('Preencha as informações do usuário')
                            ->icon(Heroicon::User)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('registration_number')
                                            ->label('Número de matrícula')
                                            ->placeholder('12345')
                                            ->numeric()
                                            ->required(),

                                        TextInput::make('name')
                                            ->label('Nome do Estudante')
                                            ->placeholder('João Silva Lima')
                                            ->required()
                                    ]),

                                Grid::make(2)
                                    ->schema([
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

                                        TextInput::make('email')
                                            ->label('E-mail acadêmico')
                                            ->placeholder('12345@edu.br')
                                            ->dehydrated()
                                            ->helperText(function (UtilitiesGet $get) {
                                                $registration = $get('registration_number');

                                                return $registration
                                                    ? "Sugestão: {$registration}@edu.br"
                                                    : 'Digite a matrícula para gerar uma sugestão.';
                                            })
                                            ->suffixAction(
                                                Action::make('useSuggestedEmail')
                                                    ->label('Usar sugestão')
                                                    ->icon(Heroicon::Envelope)
                                                    ->action(function (UtilitiesGet $get, UtilitiesSet $set) {
                                                        $registration = $get('registration_number');

                                                        if ($registration) {
                                                            $set('email', "{$registration}@edu.br");
                                                        }
                                                    })
                                            )
                                    ]),


                            ]),
                    ])
                ]),
        ];
    }
}

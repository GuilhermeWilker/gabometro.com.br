<?php

namespace App\Filament\Resources\ClassRooms\Pages;

use App\Filament\Resources\ClassRooms\ClassRoomResource;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

class ListClassRooms extends ListRecords
{
    protected static string $resource = ClassRoomResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['school_id'] = Filament::getTenant()->getKey();

        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova Turma')
                ->icon(Heroicon::Plus)
                ->schema([
                    Wizard::make([
                        Step::make('Informações da Turma')
                            ->description('Preencha as informações da turma')
                            ->icon(Heroicon::AcademicCap)
                            ->schema([
                                Select::make('grade_level')
                                    ->label('Série')
                                    ->options([
                                        '1º Ano Ensino Médio' => '1º Ano Ensino Médio',
                                        '2º Ano Ensino Médio' => '2º Ano Ensino Médio',
                                        '3º Ano Ensino Médio' => '3º Ano Ensino Médio',
                                        '6º Ano' => '6º Ano',
                                        '7º Ano' => '7º Ano',
                                        '8º Ano' => '8º Ano',
                                        '9º Ano' => '9º Ano',
                                    ])
                                    ->required(),

                                Select::make('section')
                                    ->label('Turma')
                                    ->options([
                                        'A' => 'A',
                                        'B' => 'B',
                                        'C' => 'C',
                                        'D' => 'D',
                                        'E' => 'E',
                                        'F' => 'F',
                                        'G' => 'G',
                                    ])
                                    ->required(),

                                TextInput::make('academic_year')
                                    ->label('Ano Acadêmico')
                                    ->numeric()
                                    ->default(now()->year)
                                    ->required(),
                            ]),
                    ]),
                ]),
        ];
    }
}

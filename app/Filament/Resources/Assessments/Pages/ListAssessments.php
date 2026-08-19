<?php

namespace App\Filament\Resources\Assessments\Pages;

use App\Filament\Resources\Assessments\AssessmentResource;
use App\Models\ClassRoom;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

class ListAssessments extends ListRecords
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->schema([
                    Wizard::make([
                        Step::make('Informações Gerais')
                            ->description('Turma, nome e data do simulado')
                            ->icon(Heroicon::DocumentChartBar)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('class_room_id')
                                            ->label('Turma')
                                            ->relationship('classRoom', 'grade_level')
                                            ->getOptionLabelFromRecordUsing(
                                                fn(ClassRoom $record) => "{$record->grade_level} - {$record->section}"
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                // Só sugere o nome se o campo ainda estiver vazio
                                                // (não sobrescreve o que o coordenador já digitou)
                                                if (blank($state) || filled($get('name'))) {
                                                    return;
                                                }

                                                $classRoom = ClassRoom::find($state);

                                                if ($classRoom) {
                                                    $set(
                                                        'name',
                                                        "Simulado - {$classRoom->grade_level} {$classRoom->section} " . now()->format('d/m/Y')
                                                    );
                                                }
                                            }),

                                        TextInput::make('name')
                                            ->label('Nome do simulado')
                                            ->placeholder('Simulado ENEM - 2015')
                                            ->required()
                                            ->maxLength(255),

                                        DatePicker::make('assessment_date')
                                            ->label('Data de realização')
                                            ->default(now())
                                            ->native(false)
                                            ->displayFormat('d/m/Y')
                                            ->required(),
                                    ]),
                            ]),

                        Step::make('Planilha de Resultados')
                            ->description('Upload do arquivo com os resultados')
                            ->icon(Heroicon::ArrowUpTray)
                            ->schema([
                                FileUpload::make('spreadsheet_path')
                                    ->label('Planilha (.xlsx)')
                                    ->disk('local')
                                    ->directory('assessments/spreadsheets')
                                    ->visibility('private')
                                    ->acceptedFileTypes([
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    ])
                                    ->required()
                                    ->helperText(
                                        'Baixe a planilha modelo, para ter maior acertividade no resultado.'
                                    ),
                            ]),
                    ]),
                ])
                ->mutateDataUsing(function (array $data): array {
                    $data['status'] = 'pending';
                    return $data;
                })
                // assim que o Assessment é criado, processa a planilha automaticamente
                ->after(function ($record): void {
                    dd($record);
                }),
        ];
    }
}

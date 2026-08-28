<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\School;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class RegisterSchool extends RegisterTenant
{
      public static function getLabel(): string
      {
            return 'Registrar escola';
      }

      public function form(Schema $schema): Schema
      {
            return $schema
                  ->components([
                        Wizard::make([
                              Step::make('Informações da Escola')
                                    ->description('Defina o nome e o identificador da escola')
                                    ->icon(Heroicon::BuildingOffice2)
                                    ->schema([
                                          TextInput::make('name')
                                                ->label('Nome da escola')
                                                ->placeholder('Ex: Colégio Rainha da Paz')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(
                                                      fn($state, callable $set) => $set('slug', Str::slug($state))
                                                ),

                                          TextInput::make('slug')
                                                ->label('Slug (URL)')
                                                ->placeholder('Ex: colegio-rainha-da-paz')
                                                ->helperText('Usado na URL do painel. Será gerado automaticamente a partir do nome.')
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(School::class)
                                                ->alphaDash(),
                                    ]),
                        ])
                              ->skippable(false),
                  ]);
      }

      protected function handleRegistration(array $data): School
      {
            $school = School::create($data);

            $school->members()->attach(auth()->id());

            return $school;
      }
}

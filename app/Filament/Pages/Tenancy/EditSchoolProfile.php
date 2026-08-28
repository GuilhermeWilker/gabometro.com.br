<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class EditSchoolProfile extends EditTenantProfile
{
      public static function getLabel(): string
      {
            return 'Perfil da escola';
      }

      public function form(Schema $schema): Schema
      {
            return $schema
                  ->components([
                        Section::make('Informações da Escola')
                              ->description('Atualize os dados da sua organização')
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
                                          ->helperText('Cuidado: alterar o slug muda a URL do painel desta escola.')
                                          ->required()
                                          ->maxLength(255)
                                          ->unique(ignoreRecord: true)
                                          ->alphaDash(),
                              ]),
                  ]);
      }
}

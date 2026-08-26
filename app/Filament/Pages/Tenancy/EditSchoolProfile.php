<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

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
                        TextInput::make('name')
                              ->label('Nome da escola')
                              ->required()
                              ->maxLength(255),

                        TextInput::make('slug')
                              ->label('Slug')
                              ->required()
                              ->maxLength(255)
                              ->unique(ignoreRecord: true),
                  ]);
      }
}

<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\School;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
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
                        TextInput::make('name')
                              ->label('Nome da escola')
                              ->required()
                              ->maxLength(255)
                              ->live(onBlur: true)
                              ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                              ->label('Slug')
                              ->required()
                              ->maxLength(255)
                              ->unique(School::class),
                  ]);
      }

      protected function handleRegistration(array $data): School
      {
            $school = School::create($data);

            $school->members()->attach(auth()->id());

            return $school;
      }
}

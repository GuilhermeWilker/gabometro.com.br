<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\School;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\CepFieldMode;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\Enums\CepFieldMode as EnumsCepFieldMode;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Leandrocfe\FilamentPtbrFormFields\Providers\ViaCepProvider;

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
                        Section::make('Dados da escola')
                              ->icon(Heroicon::BuildingOffice2)
                              ->columns(2)
                              ->schema([
                                    TextInput::make('name')
                                          ->label('Nome fantasia')
                                          ->required()
                                          ->maxLength(255)
                                          ->live(onBlur: true)
                                          ->afterStateUpdated(
                                                fn($state, Set $set) => $set('slug', Str::slug($state ?? ''))
                                          ),

                                    TextInput::make('legal_name')
                                          ->label('Razão social')
                                          ->maxLength(255),

                                    Document::make('cnpj')
                                          ->label('CNPJ')
                                          ->cnpj()
                                          ->unique(School::class, ignoreRecord: true),

                                    TextInput::make('slug')
                                          ->label('Slug (URL)')
                                          ->required()
                                          ->maxLength(255)
                                          ->unique(ignoreRecord: true)
                                          ->alphaDash()
                                          ->helperText('Alterar o slug muda a URL do painel.'),

                                    Select::make('institution_type')
                                          ->label('Tipo de instituição')
                                          ->options([
                                                'particular' => 'Particular',
                                                'publica' => 'Pública',
                                                'rede' => 'Rede de ensino',
                                                'cursinho' => 'Cursinho / Preparatório',
                                                'outro' => 'Outro',
                                          ])
                                          ->native(false),

                                    FileUpload::make('logo_path')
                                          ->label('Logo')
                                          ->image()
                                          ->directory('schools/logos')
                                          ->imageEditor()
                                          ->columnSpanFull(),
                              ]),

                        Section::make('Contato')
                              ->icon(Heroicon::Phone)
                              ->columns(2)
                              ->schema([
                                    TextInput::make('email')
                                          ->label('E-mail de contato')
                                          ->email()
                                          ->required(),

                                    PhoneNumber::make('phone')
                                          ->label('Telefone / WhatsApp')
                                          ->required(),

                                    TextInput::make('responsible_name')
                                          ->label('Nome do responsável')
                                          ->required(),

                                    TextInput::make('responsible_role')
                                          ->label('Cargo do responsável'),
                              ]),

                        Section::make('Endereço')
                              ->icon(Heroicon::MapPin)
                              ->columns(2)
                              ->schema([
                                    Cep::make('zip_code')
                                          ->label('CEP')
                                          ->mode(EnumsCepFieldMode::SUFFIX)
                                          ->api(ViaCepProvider::class, function (Set $set, ?array $response) {
                                                $set('street', data_get($response, 'logradouro'));
                                                $set('district', data_get($response, 'bairro'));
                                                $set('city', data_get($response, 'localidade'));
                                                $set('state', data_get($response, 'uf'));
                                          }),

                                    TextInput::make('street')->label('Rua / Logradouro'),
                                    TextInput::make('number')->label('Número'),
                                    TextInput::make('complement')->label('Complemento'),
                                    TextInput::make('district')->label('Bairro'),
                                    TextInput::make('city')->label('Cidade'),
                                    TextInput::make('state')->label('UF')->maxLength(2)->length(2),
                              ]),
                  ]);
      }
}

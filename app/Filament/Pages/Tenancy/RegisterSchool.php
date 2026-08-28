<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\School;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\CepFieldMode;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\Enums\CepFieldMode as EnumsCepFieldMode;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Leandrocfe\FilamentPtbrFormFields\Providers\ViaCepProvider;

class RegisterSchool extends RegisterTenant
{
      public static function getLabel(): string
      {
            return 'Registrar escola';
      }

      public function getMaxContentWidth(): string | null
      {
            return '7xl'; // opções: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full
      }

      public function form(Schema $schema): Schema
      {
            return $schema
                  ->components([
                        Wizard::make([
                              Step::make('Dados da escola')
                                    ->description('Informações básicas e identificação')
                                    ->icon(Heroicon::BuildingOffice2)
                                    ->schema([
                                          TextInput::make('name')
                                                ->label('Nome fantasia')
                                                ->placeholder('Ex: Colégio Rainha da Paz')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(
                                                      fn($state, Set $set) => $set('slug', Str::slug($state ?? ''))
                                                ),

                                          TextInput::make('legal_name')
                                                ->label('Razão social')
                                                ->placeholder('Ex: Colégio Rainha da Paz Ltda')
                                                ->maxLength(255),

                                          Document::make('cnpj')
                                                ->label('CNPJ')
                                                ->cnpj()
                                                ->unique(School::class)
                                                ->required(),

                                          TextInput::make('slug')
                                                ->label('Slug (URL)')
                                                ->helperText('Usado na URL do painel. Gerado automaticamente.')
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(School::class)
                                                ->alphaDash(),

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
                                                ->nullable(),
                                    ]),

                              Step::make('Contato')
                                    ->description('Dados para comunicação e suporte')
                                    ->icon(Heroicon::Phone)
                                    ->schema([
                                          TextInput::make('email')
                                                ->label('E-mail de contato')
                                                ->email()
                                                ->required()
                                                ->maxLength(255),

                                          PhoneNumber::make('phone')
                                                ->label('Telefone / WhatsApp')
                                                ->required(),

                                          TextInput::make('responsible_name')
                                                ->label('Nome do responsável')
                                                ->required()
                                                ->maxLength(255),

                                          TextInput::make('responsible_role')
                                                ->label('Cargo do responsável')
                                                ->placeholder('Ex: Diretor, Coordenador pedagógico')
                                                ->maxLength(255),
                                    ]),

                              Step::make('Endereço')
                                    ->description('Localização da escola (opcional no início)')
                                    ->icon(Heroicon::MapPin)
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

                                          TextInput::make('street')
                                                ->label('Rua / Logradouro')
                                                ->maxLength(255),

                                          TextInput::make('number')
                                                ->label('Número')
                                                ->maxLength(20),

                                          TextInput::make('complement')
                                                ->label('Complemento')
                                                ->maxLength(255),

                                          TextInput::make('district')
                                                ->label('Bairro')
                                                ->maxLength(255),

                                          TextInput::make('city')
                                                ->label('Cidade')
                                                ->maxLength(255),

                                          TextInput::make('state')
                                                ->label('UF')
                                                ->maxLength(2)
                                                ->length(2),
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

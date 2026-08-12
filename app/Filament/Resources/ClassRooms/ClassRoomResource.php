<?php

namespace App\Filament\Resources\ClassRooms;

use App\Filament\Resources\ClassRooms\Pages\ManageClassRooms;
use App\Models\ClassRoom;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cube;
    protected static string | UnitEnum | null $navigationGroup = 'Configurações da Organização';

    protected static ?string $recordTitleAttribute = 'Classrooms list';
    protected static ?string $navigationLabel = 'Salas de Aula';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grade_level')->options([
                    '1º Ano do Ensino Médio' => '1º Ano do Ensino Médio',
                    '2º Ano do Ensino Médio' => '2º Ano do Ensino Médio',
                    '3º Ano do Ensino Médio' => '3º Ano do Ensino Médio',
                    '4º Ano do Ensino Fundamental' => '4º Ano do Ensino Fundamental',
                    '5º Ano do Ensino Fundamental' => '5º Ano do Ensino Fundamental',
                    '6º Ano do Ensino Fundamental' => '6º Ano do Ensino Fundamental',
                    '7º Ano do Ensino Fundamental' => '7º Ano do Ensino Fundamental',
                    '8º Ano do Ensino Fundamental' => '8º Ano do Ensino Fundamental',
                    '9º Ano do Ensino Fundamental' => '9º Ano do Ensino Fundamental',
                ]),
                Select::make('section')->options([
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G',
                    'H' => 'H',
                    'I' => 'I',
                    'J' => 'J',
                ]),

                TextInput::make('academic_year')->placeholder(date('Y')),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('grade_level'),
                TextEntry::make('section')
                    ->placeholder('-'),
                TextEntry::make('academic_year')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Classrooms list')
            ->columns([
                TextColumn::make('grade_level')
                    ->label('Série')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('section')
                    ->label('Turma')
                    ->searchable(),
                TextColumn::make('academic_year')
                    ->label('Ano Letivo')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageClassRooms::route('/'),
        ];
    }
}

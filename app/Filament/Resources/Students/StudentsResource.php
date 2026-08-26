<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudents;
use App\Filament\Resources\Students\Pages\EditStudents;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\ViewStudents;
use App\Filament\Resources\Students\Schemas\StudentsForm;
use App\Filament\Resources\Students\Schemas\StudentsInfolist;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Models\Students;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class StudentsResource extends Resource
{
    protected static ?string $model = Students::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;
    protected static string | UnitEnum | null $navigationGroup = 'Acadêmico';

    protected static ?string $recordTitleAttribute = 'Students list';
    protected static ?string $label = 'Lista de estudantes';
    protected static ?string $navigationLabel = 'Estudantes';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('school_id', Filament::getTenant()->getKey());
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return StudentsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            // 'create' => CreateStudents::route('/create'),
            'view' => ViewStudents::route('/{record}'),
            // 'edit' => EditStudents::route('/{record}/edit'),
        ];
    }
}

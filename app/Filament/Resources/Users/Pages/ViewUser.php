<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
    protected ?string $subheading = "Detalhes do Usuário";

    protected function getHeaderActions(): array
    {

        return [
            EditAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return "{$this->record->name}";
    }
}

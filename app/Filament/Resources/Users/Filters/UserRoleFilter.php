<?php

namespace App\Filament\Resources\Users\Filters;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class UserRoleFilter
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function make()
    {
        return SelectFilter::make('role')->options([
            'admin' => 'Administrator',
            'coordinator' => 'Coordinator',
            'teacher' => 'Teacher',
        ])->placeholder('Everyone');
    }
}

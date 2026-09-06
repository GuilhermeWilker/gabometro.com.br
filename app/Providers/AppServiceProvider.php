<?php

namespace App\Providers;

use App\Models\Assessment;
use App\Models\ClassRoom;
use App\Models\Students;
use App\Policies\AssessmentPolicy;
use App\Policies\ClassRoomPolicy;
use App\Policies\StudentsPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Students::class, StudentsPolicy::class);
        Gate::policy(Assessment::class, AssessmentPolicy::class);
        Gate::policy(ClassRoom::class, ClassRoomPolicy::class);
    }
}

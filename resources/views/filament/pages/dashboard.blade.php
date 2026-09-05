<x-filament-panels::page>
    <div class="md:ml-30">
        {{-- Header fino --}}
        <div class="mb-4 flex items-end justify-between gap-3">
            <div>
                <h2
                    class="text-3xl md:text-7xl mb-4 font-black text-gray-900 dark:text-white underline decoration-indigo-600 decoration-4 decoration-wavy underline-offset-8">
                    {{ $this->getSchoolMeta()['name'] }}
                </h2>
                <p class="text-md text-gray-500 dark:text-gray-400">
                    Visão geral do desempenho
                </p>
            </div>
        </div>

        {{-- KPIs + gauge na mesma altura --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 mb-4">
            <div class="md:col-span-2">
                @livewire(\App\Filament\Widgets\SchoolOverallGauge::class, key('school-gauge'))
            </div>

            <div class="md:col-span-7">
                @livewire(\App\Filament\Widgets\SchoolKpiStats::class, key('school-kpi'))
            </div>
        </div>

        {{-- Meio --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4">
            <div class="lg:col-span-5">
                @livewire(\App\Filament\Widgets\SubjectPerformanceChart::class, key('subject-chart'))
            </div>

            <div class="lg:col-span-5">
                @livewire(\App\Filament\Widgets\CriticalSubjectsList::class, key('critical-subjects'))
            </div>

        </div>

        {{-- Base --}}
        <div class="w-7xl">
            @livewire(\App\Filament\Widgets\LatestAssessments::class, key('latest-assessments'))
        </div>
    </div>
</x-filament-panels::page>

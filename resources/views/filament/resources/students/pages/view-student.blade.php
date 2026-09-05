@php

@endphp

<x-filament-panels::page>
    {{-- Header meta --}}
    <div class="mb-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $this->getStudentMeta()['name'] }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Matrícula {{ $this->getStudentMeta()['registration'] }}
                    · {{ $this->getStudentMeta()['class'] }}
                    · {{ $this->getStudentMeta()['email'] }}
                </p>
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="md:col-span-1">
            @livewire(\App\Filament\Resources\Students\Widgets\StudentOverallGauge::class, ['record' => $this->record], key('gauge-' . $this->record->id))
        </div>
        <div class="md:col-span-3">
            @livewire(\App\Filament\Resources\Students\Widgets\StudentKpiStats::class, ['record' => $this->record], key('kpi-' . $this->record->id))
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        @livewire(\App\Filament\Resources\Students\Widgets\StudentSubjectPerformance::class, ['record' => $this->record], key('subj-' . $this->record->id))
        @livewire(\App\Filament\Resources\Students\Widgets\StudentEvolutionChart::class, ['record' => $this->record], key('evo-' . $this->record->id))
    </div>

    {{-- PDFs --}}
    <div>
        @livewire(\App\Filament\Widgets\StudentPdfsTable::class, ['record' => $this->record], key('pdfs-' . $this->record->id))
    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    {{-- Header na pegada do aluno --}}
    <div
        class="mb-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 px-5 py-4 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $this->getClassMeta()['title'] }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Ano letivo {{ $this->getClassMeta()['year'] }}
                    · {{ $this->getClassMeta()['students_count'] }}
                    {{ $this->getClassMeta()['students_count'] === 1 ? 'aluno' : 'alunos' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Relation managers / tabela de alunos --}}
    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers :active-manager="$activeRelationManager ?? null" :managers="$relationManagers" :owner-record="$record"
            :page-class="static::class" />
    @endif
</x-filament-panels::page>

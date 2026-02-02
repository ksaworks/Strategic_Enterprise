<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-bolt" class="h-5 w-5 text-primary-500" />
                <span>Ações Rápidas</span>
            </div>
        </x-slot>

        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('filament.admin.resources.tasks.create') }}" 
               class="group flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-lg transition-all duration-200">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 group-hover:scale-110 transition-transform">
                    <x-filament::icon icon="heroicon-o-clipboard-document-check" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Nova Tarefa</span>
            </a>

            <a href="{{ route('filament.admin.resources.projects.create') }}" 
               class="group flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-lg transition-all duration-200">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 group-hover:scale-110 transition-transform">
                    <x-filament::icon icon="heroicon-o-folder-plus" class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Novo Projeto</span>
            </a>

            <a href="{{ route('filament.admin.resources.project-demands.create') }}" 
               class="group flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-lg transition-all duration-200">
                <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900/30 group-hover:scale-110 transition-transform">
                    <x-filament::icon icon="heroicon-o-inbox-arrow-down" class="h-6 w-6 text-amber-600 dark:text-amber-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Nova Demanda</span>
            </a>

            <a href="{{ route('filament.admin.resources.meetings.create') }}" 
               class="group flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-primary-500 dark:hover:border-primary-500 hover:shadow-lg transition-all duration-200">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 group-hover:scale-110 transition-transform">
                    <x-filament::icon icon="heroicon-o-calendar-days" class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Agendar Reunião</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

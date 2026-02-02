<x-filament-panels::page>
    {{-- Custom styles for the Strategy Map --}}
    <style>
        .strategy-map-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            min-height: 70vh;
        }
        
        .perspective-lane {
            position: relative;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .perspective-lane::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: -1;
        }
        
        .dark .perspective-lane::before {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
        }
        
        .perspective-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .perspective-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(5px);
        }
        
        .objective-card {
            position: relative;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.75rem;
            padding: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
        }
        
        .objective-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.3);
            background: rgba(255,255,255,0.12);
        }
        
        .dark .objective-card {
            background: rgba(30,30,40,0.6);
            border-color: rgba(255,255,255,0.08);
        }
        
        .dark .objective-card:hover {
            background: rgba(40,40,55,0.8);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.5);
        }
        
        .objective-code-badge {
            position: absolute;
            top: -0.5rem;
            right: 0.75rem;
            font-size: 0.65rem;
            font-weight: 700;
            font-family: ui-monospace, SFMono-Regular, monospace;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            letter-spacing: 0.05em;
        }
        
        .fcs-list {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed rgba(255,255,255,0.15);
        }
        
        .fcs-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.75rem;
            padding: 0.25rem 0;
            color: rgba(255,255,255,0.7);
        }
        
        .dark .fcs-item {
            color: rgba(255,255,255,0.6);
        }
        
        .fcs-bullet {
            flex-shrink: 0;
            width: 0.375rem;
            height: 0.375rem;
            margin-top: 0.375rem;
            border-radius: 50%;
        }
        
        .empty-perspective {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed rgba(255,255,255,0.15);
            border-radius: 0.75rem;
            color: rgba(255,255,255,0.4);
            font-style: italic;
            font-size: 0.875rem;
        }
        
        .empty-map-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            text-align: center;
        }
        
        .empty-map-icon {
            width: 4rem;
            height: 4rem;
            padding: 1rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(99,102,241,0.2) 0%, rgba(168,85,247,0.2) 100%);
            margin-bottom: 1.5rem;
        }
    </style>

    <div class="strategy-map-container">
        @forelse($perspectives as $perspective)
            <div class="perspective-lane" style="border-left: 4px solid {{ $perspective->color }}; background: linear-gradient(90deg, {{ $perspective->color }}10 0%, transparent 100%);">
                
                {{-- Perspective Header --}}
                <div class="perspective-header">
                    @if($perspective->icon)
                        <div class="perspective-icon-wrapper" style="background: {{ $perspective->color }}30;">
                            <x-filament::icon
                                :icon="trim($perspective->icon)"
                                class="h-6 w-6"
                                style="color: {{ $perspective->color }}"
                            />
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">
                            {{ $perspective->name }}
                        </h2>
                        @if($perspective->description)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">
                                {{ $perspective->description }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium px-2 py-1 rounded-full" style="background: {{ $perspective->color }}20; color: {{ $perspective->color }};">
                            {{ $perspective->strategicObjectives->count() }} {{ Str::plural('objetivo', $perspective->strategicObjectives->count()) }}
                        </span>
                    </div>
                </div>

                {{-- Objectives Grid --}}
                @if($perspective->strategicObjectives->isEmpty())
                    <div class="empty-perspective">
                        <x-filament::icon icon="heroicon-o-plus-circle" class="h-5 w-5 mr-2 opacity-50" />
                        Adicione objetivos estratégicos a esta perspectiva
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($perspective->strategicObjectives as $objective)
                            <div class="objective-card">
                                
                                {{-- Code Badge --}}
                                @if($objective->code)
                                    <div class="objective-code-badge" style="background: {{ $perspective->color }}; color: white;">
                                        {{ $objective->code }}
                                    </div>
                                @endif
                                
                                {{-- Objective Title --}}
                                <h3 class="font-semibold text-gray-800 dark:text-gray-100 pr-12 leading-snug">
                                    {{ $objective->name }}
                                </h3>
                                
                                {{-- Owner --}}
                                @if($objective->owner)
                                    <div class="flex items-center gap-1.5 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        <x-filament::icon icon="heroicon-m-user-circle" class="h-4 w-4" style="color: {{ $perspective->color }};" />
                                        <span>{{ $objective->owner->name }}</span>
                                    </div>
                                @endif
                                
                                {{-- Critical Success Factors --}}
                                @if($objective->criticalSuccessFactors->isNotEmpty())
                                    <div class="fcs-list">
                                        <div class="text-[0.65rem] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">
                                            Fatores Críticos
                                        </div>
                                        @foreach($objective->criticalSuccessFactors->take(4) as $fcs)
                                            <div class="fcs-item">
                                                <span class="fcs-bullet" style="background: {{ $perspective->color }};"></span>
                                                <span>{{ $fcs->name }}</span>
                                            </div>
                                        @endforeach
                                        @if($objective->criticalSuccessFactors->count() > 4)
                                            <div class="text-xs text-gray-400 mt-1 pl-3">
                                                +{{ $objective->criticalSuccessFactors->count() - 4 }} mais...
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            {{-- Empty State --}}
            <div class="empty-map-state">
                <div class="empty-map-icon">
                    <x-filament::icon icon="heroicon-o-presentation-chart-line" class="h-full w-full text-indigo-400" />
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
                    Mapa Estratégico Vazio
                </h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md">
                    Comece cadastrando <strong>Perspectivas</strong> e <strong>Objetivos Estratégicos</strong> no menu lateral para visualizar seu mapa.
                </p>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>

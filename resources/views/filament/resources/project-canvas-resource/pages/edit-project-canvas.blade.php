<x-filament-panels::page>
    <style>
        .pmc-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(2, minmax(200px, auto));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .pmc-section {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            min-height: 200px;
        }

        .dark .pmc-section {
            background-color: #1f2937;
            border-color: #374151;
        }

        .pmc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            border-bottom: 2px solid;
            padding-bottom: 0.25rem;
        }
        
        .pmc-title {
            font-weight: bold;
            font-size: 0.875rem;
            text-transform: uppercase;
        }
        
        .pmc-items {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-grow: 1;
        }

        .pmc-postit {
            padding: 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            cursor: pointer;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: transform 0.1s;
            color: #1f2937; 
        }
        
        .pmc-postit:hover {
            transform: scale(1.02);
            z-index: 10;
        }

        .pmc-empty-state {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            color: #9ca3af;
            border: 1px dashed #4b5563;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
            align-self: flex-start;
        }
        
        .pmc-empty-state:hover {
            border-color: #6b7280;
            color: #d1d5db;
            background: rgba(255,255,255,0.03);
        }
        
        .pmc-empty-state svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        /* Cores Post-it */
        .postit-yellow { background-color: #fef3c7; border-left: 4px solid #f59e0b; }
        .postit-green { background-color: #d1fae5; border-left: 4px solid #10b981; }
        .postit-red { background-color: #fee2e2; border-left: 4px solid #ef4444; }
        .postit-blue { background-color: #dbeafe; border-left: 4px solid #3b82f6; }

        /* Grid Areas - Metodologia PMC */
        /* Linha 1: Porquê (Justif, Obj, Ben) -> O que (Prod, Req) */
        .area-pit { grid-column: 1; grid-row: 1; }
        .area-justification { grid-column: 2; grid-row: 1; }
        .area-smart_obj { grid-column: 3; grid-row: 1; }
        .area-benefits { grid-column: 4; grid-row: 1; }
        .area-product { grid-column: 5; grid-row: 1; }

        /* Linha 2: Quem (Stake, Equipe) -> Como (Prem, Entr) -> Riscos */
        .area-requirements { grid-column: 1; grid-row: 2; }
        .area-stakeholders { grid-column: 2; grid-row: 2; }
        .area-team { grid-column: 3; grid-row: 2; }
        .area-premises { grid-column: 4; grid-row: 2; }
        .area-deliverables { grid-column: 5; grid-row: 2; }
        
        /* Linha 3 (Rodapé): Riscos, Linha do Tempo, Custos */
        .area-risks { grid-column: 1 / span 2; grid-row: 3; }
        .area-timeline { grid-column: 3 / span 2; grid-row: 3; }
        .area-costs { grid-column: 5; grid-row: 3; }
        
        /* Tablet responsive (landscape and small laptops) */
        @media (max-width: 1280px) {
            .pmc-grid {
                grid-template-columns: repeat(3, 1fr); /* 3 colunas */
                grid-template-rows: auto; /* Altura automática */
            }
            
            /* Resetando posições específicas para fluxo natural ou reordenado */
            .pmc-section { grid-column: auto; grid-row: auto; }
            
            /* Agrupamentos lógicos para tablet */
            .area-pit { grid-column: 1; grid-row: 1; }
            .area-justification { grid-column: 2; grid-row: 1; }
            .area-smart_obj { grid-column: 3; grid-row: 1; }
            
            .area-benefits { grid-column: 1; grid-row: 2; }
            .area-product { grid-column: 2; grid-row: 2; }
            .area-requirements { grid-column: 3; grid-row: 2; }
            
            .area-stakeholders { grid-column: 1; grid-row: 3; }
            .area-team { grid-column: 2; grid-row: 3; }
            .area-premises { grid-column: 3; grid-row: 3; }
            
            .area-deliverables { grid-column: 1; grid-row: 4; }
            .area-risks { grid-column: 2; grid-row: 4; }
            .area-costs { grid-column: 3; grid-row: 4; }

            .area-timeline { grid-column: 1 / span 3; grid-row: 5; }
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .pmc-grid {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }
            .pmc-section {
                width: 100%;
                min-height: 150px; /* Menor altura mínima em mobile */
            }
        }
    </style>

    @php
        $groupedItems = $record->items_grouped_by_section;
    @endphp

    <div class="pmc-grid" x-data="{ draggingItemId: null }">
        @foreach($this->getSections() as $section)
            <div 
                class="pmc-section area-{{ $section->value }}" 
                style="border-top-color: var(--{{ $section->getColor() }}-500)"
                @dragover.prevent
                @drop.prevent="$wire.updateItemSection(draggingItemId, '{{ $section->value }}'); draggingItemId = null;"
            >
                <div class="pmc-header" style="border-bottom-color: var(--{{ $section->getColor() }}-500); color: rgb(var(--{{ $section->getColor() }}-500))">
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="{{ $section->getIcon() }}" class="h-4 w-4" />
                        <span class="pmc-title">{{ $section->getLabel() }}</span>
                    </div>
                    <button wire:click="addItem('{{ $section->value }}')" class="hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1" title="Adicionar Item">
                        <x-heroicon-o-plus class="w-4 h-4" />
                    </button>
                </div>
                
                <div class="pmc-items">
                    @if(isset($groupedItems[$section->value]) && count($groupedItems[$section->value]) > 0)
                        @foreach($groupedItems[$section->value] as $item)
                            <div 
                                class="pmc-postit postit-{{ $item->color }}" 
                                wire:click="editItem({{ $item->id }})"
                                draggable="true"
                                @dragstart="draggingItemId = {{ $item->id }}"
                            >
                                <div class="font-bold">{{ $item->title }}</div>
                                @if($item->content)
                                    <div class="text-xs opacity-75 mt-1 line-clamp-3">{{ $item->content }}</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div 
                            class="pmc-empty-state"
                            wire:click="addItem('{{ $section->value }}')"
                        >
                            <x-heroicon-o-plus />
                            <span>Clique para adicionar</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    
    <x-filament-actions::modals />
</x-filament-panels::page>

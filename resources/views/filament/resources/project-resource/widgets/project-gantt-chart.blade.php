<x-filament-widgets::widget>
    <x-filament::section>
        <div 
            x-data="{
                gantt: null,
                tasks: @js($ganttData),
                initGantt() {
                    if (!this.tasks.length) return;
                    
                    this.gantt = new Gantt('#gantt-chart', this.tasks, {
                        header_height: 50,
                        column_width: 30,
                        step: 24,
                        view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
                        bar_height: 20,
                        bar_corner_radius: 3,
                        arrow_curve: 5,
                        padding: 18,
                        view_mode: 'Week',
                        date_format: 'YYYY-MM-DD',
                        language: 'ptBr', 
                        custom_popup_html: function(task) {
                            const start_date = task._start.toLocaleDateString('pt-BR');
                            const end_date = task._end.toLocaleDateString('pt-BR');
                            return `
                                <div class='gantt-popup-details'>
                                    <div class='gantt-popup-title font-bold text-lg mb-1'>${task.name}</div>
                                    <div class='gantt-popup-date text-sm text-gray-400 mb-1'>${start_date} - ${end_date}</div>
                                    <div class='gantt-popup-progress text-xs'>Progresso: ${task.progress}%</div>
                                </div>
                            `;
                        }, 
                        on_date_change: (task, start, end) => {
                            $wire.updateTaskDate(task.id, this.formatDate(start), this.formatDate(end));
                        },
                        on_progress_change: (task, progress) => {
                           // $wire.updateTaskProgress(task.id, progress);
                        },
                        on_view_change: (mode) => {
                            console.log(mode);
                        }
                    });
                },
                changeView(mode) {
                    if(this.gantt) this.gantt.change_view_mode(mode);
                },
                formatDate(date) {
                    return date.toISOString().split('T')[0];
                }
            }"
            x-init="initGantt()"
            wire:ignore
        >
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Cronograma (Gantt)</h2>
                <div class="flex gap-2">
                    <button @click="changeView('Day')" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Dia</button>
                    <button @click="changeView('Week')" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Semana</button>
                    <button @click="changeView('Month')" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Mês</button>
                </div>
            </div>

            <div id="gantt-chart" class="overflow-x-auto"></div>
            
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
            <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

            
            <style>
                /* Custom styles for dark mode compatibility */
                .dark .gantt .grid-header {
                    fill: #1f2937;
                    stroke: #374151;
                }
                .dark .gantt .grid-row {
                    fill: #1f2937; /* Fundo das linhas */
                    stroke: #374151;
                }
                .dark .gantt .tick text {
                    fill: #d58f05 !important; /* Amarelo Ouro */
                    font-weight: 500;
                }
                .dark .gantt .upper-text {
                    fill: #d58f05 !important; /* Amarelo Ouro (Mês/Ano) */
                    font-weight: bold;
                    font-size: 14px;
                }
                .dark .gantt .lower-text {
                    fill: #e5e7eb !important; /* Texto claro para dias */
                    font-size: 12px;
                }

                /* Cores das Barras mais vibrantes */
                .bar-primary .bar { fill: #f59e0b; } /* Amber-500 */
                .bar-primary .bar-progress { fill: #d97706; } /* Amber-600 */
                
                .bar-success .bar { fill: #10b981; } /* Emerald-500 */
                .bar-success .bar-progress { fill: #059669; }
                
                .bar-danger .bar { fill: #ef4444; } /* Red-500 */
                .bar-danger .bar-progress { fill: #dc2626; }
                
                .bar-gray .bar { fill: #9ca3af; } /* Gray-400 */
                
                /* Hover effects */
                .gantt-container .bar-wrapper:hover .bar {
                    filter: brightness(1.2);
                }
                /* Custom Popup Styles */
                .gantt-container .popup-wrapper {
                    background: #1f2937 !important;
                    color: white !important;
                    padding: 10px;
                    border-radius: 6px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
                    border: 1px solid #374151;
                    opacity: 1 !important;
                    width: 250px !important;
                }
                .gantt-popup-title { color: #d58f05; font-weight: bold; }
                .gantt-popup-date { color: #d1d5db; }
            </style>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

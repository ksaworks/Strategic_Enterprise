<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Cronograma (Gantt)
        </x-slot>

        <div wire:ignore
             x-data="{
                tasks: {{ json_encode($tasks) }},
                gantt: null,
                init() {
                    if (this.tasks.length === 0) return;
                    
                    this.gantt = new Gantt('#gantt-container', this.tasks, {
                        header_height: 50,
                        column_width: 30,
                        step: 24,
                        view_modes: ['Day', 'Week', 'Month'],
                        bar_height: 20,
                        bar_corner_radius: 3,
                        arrow_curve: 5,
                        padding: 18,
                        view_mode: 'Month',
                        custom_popup_html: function(task) {
                            return `
                                <div class='details-container p-2' style='background: #0f1729; color: #fff; border-radius: 4px; border: 1px solid #d58f05;'>
                                    <div class='font-bold' style='color: #d58f05;'>${task.name}</div>
                                    <div class='text-xs'>Início: ${task.start}</div>
                                    <div class='text-xs'>Fim: ${task.end}</div>
                                    <div class='text-xs'>Progresso: ${task.progress}%</div>
                                </div>
                            `;
                        }
                    });
                }
             }"
             id="gantt-wrapper"
             class="w-full overflow-hidden"
        >
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.min.js"></script>

            <style>
                #gantt-container .bar-planning { fill: #9ca3af; }
                #gantt-container .bar-success { fill: #10b981; }
                #gantt-container .bar-standard { fill: #d58f05; }
                #gantt-container .bar-progress { fill: #1e3a8a; }
                .details-container { min-width: 150px; }
            </style>

            @if(count($tasks) > 0)
                <div id="gantt-container" class="w-full"></div>
            @else
                <div class="flex items-center justify-center p-8 text-gray-500 italic">
                    Nenhuma tarefa com datas definidas para exibir no Gantt.
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

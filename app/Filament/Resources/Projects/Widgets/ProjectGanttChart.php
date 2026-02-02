<?php

namespace App\Filament\Resources\Projects\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Services\GanttService;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class ProjectGanttChart extends Widget
{
    protected string $view = 'filament.resources.project-resource.widgets.project-gantt-chart';
    
    public ?Model $record = null;
    public $ganttData = [];

    protected int | string | array $columnSpan = 'full';

    public function mount()
    {
        if ($this->record instanceof Project) {
            $this->loadData();
        }
    }

    public function loadData()
    {
        $service = new GanttService();
        $this->ganttData = $service->getGanttData($this->record);
    }

    public function updateTaskDate($taskId, $start, $end)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update([
                'start_date' => $start,
                'end_date' => $end,
            ]);
            
            $this->loadData(); // Reload data to reflect changes if necessary
            $this->dispatch('gantt-updated');
        }
    }
}

<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Task;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

use BackedEnum;

class ProjectWbs extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ProjectResource::class;

    protected string $view = 'filament.resources.projects.pages.project-wbs';

    protected static ?string $title = 'E.A.P Gráfica';
    
    protected static ?string $navigationLabel = 'E.A.P Gráfica';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-share';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTreeData(): array
    {
        // Carrega tarefas raiz do projeto com seus filhos recursivamente
        $rootTasks = $this->record->tasks()
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->with('children'); // Ajustar profundidade conforme necessário ou usar recursividade manual
            }])
            ->orderBy('start_date')
            ->get();

        return $this->buildTree($rootTasks);
    }

    protected function buildTree($tasks)
    {
        return $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'name' => $task->name,
                'status' => $task->status->label(),
                'status_color' => $task->status->getColor(),
                'progress' => $task->progress,
                'start_date' => $task->start_date?->format('d/m/Y'),
                'end_date' => $task->end_date?->format('d/m/Y'),
                'children' => $task->children->isNotEmpty() ? $this->buildTree($task->children) : [],
            ];
        })->toArray();
    }
}


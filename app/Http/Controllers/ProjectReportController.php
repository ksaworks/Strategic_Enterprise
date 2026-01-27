<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProjectReportController extends Controller
{
    public function download(Project $project)
    {
        $project->load([
            'company',
            'owner',
            'tasks' => function ($query) {
                $query->orderBy('end_date');
            }
        ]);

        // Calculate additional metrics if needed
        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->where('status', 'done')->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $pdf = Pdf::loadView('reports.projects.status', [
            'project' => $project,
            'progress' => $progress,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream("relatorio-projeto-{$project->id}.pdf");
    }
}

<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/reports/projects/{project}/status', [\App\Http\Controllers\ProjectReportController::class, 'download'])
        ->name('reports.projects.status');

    Route::get('/reports/departments/tasks/delayed', [\App\Http\Controllers\DepartmentTaskReportController::class, 'download'])
        ->name('reports.departments.delayed');

    Route::get('/project-canvases/{record}/pdf', function (\App\Models\ProjectCanvas $record) {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.project-canvas', [
            'record' => $record,
            'items' => $record->items->groupBy('section')
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('canvas-' . $record->id . '.pdf');
    })->name('project-canvas.pdf');
});

Route::get('/', function () {
    return redirect('/admin');
});


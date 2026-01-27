<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/reports/projects/{project}/status', [\App\Http\Controllers\ProjectReportController::class, 'download'])
        ->name('reports.projects.status');
});

Route::get('/', function () {
    return redirect('/admin');
});


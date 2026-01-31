<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DepartmentTaskReportController extends Controller
{
    public function download()
    {
        // Load Departments with Contacts, Users, and their Delayed Tasks
        $departments = Department::with(['contacts.user.ownedTasks' => function ($query) {
            $query->where('status', '!=', 3) // Assuming 3 is 'Concluído'
                  ->where('end_date', '<', now());
        }])
        ->get()
        ->map(function ($department) {
            // Flatten tasks from contacts -> user -> ownedTasks
            $tasks = $department->contacts->flatMap(function ($contact) {
                return $contact->user ? $contact->user->ownedTasks : collect();
            });
            
            // Set the 'tasks' relation manually for the view to use
            $department->setRelation('tasks', $tasks);
            return $department;
        })
        ->filter(function ($department) {
            return $department->tasks->isNotEmpty();
        });

        $pdf = Pdf::loadView('reports.departments.delayed-tasks', [
            'departments' => $departments,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('relatorio-atrasos-departamentos.pdf');
    }
}

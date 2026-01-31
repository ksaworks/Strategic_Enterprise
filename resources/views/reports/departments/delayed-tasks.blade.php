<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Relatório de Tarefas Atrasadas por Departamento</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #d58f05;
            /* Gold */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            /* Navy */
        }

        .meta {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 5px 10px;
            border-left: 4px solid #1e3a8a;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            text-align: left;
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f9fafb;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Relatório de Tarefas Atrasadas</div>
        <div class="meta">Gerado em: {{ $generatedAt }}</div>
    </div>

    @forelse($departments as $department)
        <div class="section">
            <div class="section-title">{{ $department->name }} ({{ $department->code }})</div>
            <table>
                <thead>
                    <tr>
                        <th width="40%">Tarefa</th>
                        <th width="20%">Responsável</th>
                        <th width="20%">Prazo</th>
                        <th width="20%">Atraso</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($department->tasks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->owner->name ?? 'N/A' }}</td>
                            <td>{{ $task->end_date ? $task->end_date->format('d/m/Y') : '-' }}</td>
                            <td style="color: #991b1b;">
                                {{ $task->end_date ? $task->end_date->diffInDays(now()) . ' dias' : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 20px; color: #666;">
            Nenhum departamento com tarefas atrasadas encontrado.
        </div>
    @endforelse

    <div class="footer">
        Strategic Enterprise - Relatório Gerado Automaticamente
    </div>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Relatório de Status de Projeto</title>
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

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
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
            font-size: 14px;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f9fafb;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-hold {
            background-color: #fef3c7;
            color: #92400e;
        }

        .page-break {
            page-break-after: always;
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
        <div class="title">Relatório de Status de Projeto</div>
        <div class="meta">Gerado em: {{ $generatedAt }}</div>
    </div>

    <div class="section">
        <div class="section-title">Dados do Projeto</div>
        <table>
            <tr>
                <th width="30%">Nome:</th>
                <td>{{ $project->name }}</td>
            </tr>
            <tr>
                <th>Empresa:</th>
                <td>{{ $project->company->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Responsável:</th>
                <td>{{ $project->owner->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Datas:</th>
                <td>
                    {{ $project->start_date ? $project->start_date->format('d/m/Y') : '-' }}
                    até
                    {{ $project->end_date ? $project->end_date->format('d/m/Y') : '-' }}
                </td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    <span class="badge status-{{ $project->status ?? 'default' }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Progresso:</th>
                <td>{{ $progress }}%</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Descrição</div>
        <div style="padding: 10px; background: #fff; border: 1px solid #eee;">
            {{ $project->description ?: 'Sem descrição disponível.' }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Tarefas Recentes</div>
        <table>
            <thead>
                <tr>
                    <th>Tarefa</th>
                    <th>Prazo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->tasks->take(10) as $task)
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->end_date ? $task->end_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ ucfirst($task->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">Nenhuma tarefa registrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Strategic Enterprise - Relatório Gerado Automaticamente
    </div>
</body>

</html>
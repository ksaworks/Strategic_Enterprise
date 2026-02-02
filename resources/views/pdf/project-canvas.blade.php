<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Project Model Canvas - {{ $record->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .canvas-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .canvas-table td {
            border: 1px solid #333;
            vertical-align: top;
            padding: 5px;
            height: 150px;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            margin-bottom: 5px;
            padding-bottom: 2px;
            font-size: 9px;
            color: #555;
        }
        .item {
            background-color: #f4f4f4;
            margin-bottom: 4px;
            padding: 3px;
            border-radius: 2px;
        }
        .item-title {
            font-weight: bold;
        }
        .item-content {
            margin-top: 2px;
        }
        
        /* Layout fixo 5 colunas */
        .col-width { width: 20%; }
        
        /* Colors */
        .bg-yellow { background-color: #fef3c7; }
        .bg-green { background-color: #d1fae5; }
        .bg-red { background-color: #fee2e2; }
        .bg-blue { background-color: #dbeafe; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $record->name }}</h1>
        <p>Project Model Canvas</p>
    </div>

    <table class="canvas-table">
        <!-- Linha 1 -->
        <tr>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'pit', 'label' => 'PIT de Vendas'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'justification', 'label' => 'Justificativa'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'smart_obj', 'label' => 'Obj. SMART'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'benefits', 'label' => 'Benefícios'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'product', 'label' => 'Produto'])
            </td>
        </tr>
        
        <!-- Linha 2 -->
        <tr>
             <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'requirements', 'label' => 'Requisitos'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'stakeholders', 'label' => 'Stakeholders'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'team', 'label' => 'Equipe'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'premises', 'label' => 'Premissas'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'deliverables', 'label' => 'Entregas (Grupo)'])
            </td>
        </tr>
        
        <!-- Linha 3 -->
        <tr>
            <td colspan="2">
                @include('pdf.canvas-section', ['section' => 'risks', 'label' => 'Riscos'])
            </td>
            <td colspan="2">
                @include('pdf.canvas-section', ['section' => 'timeline', 'label' => 'Linha do Tempo'])
            </td>
            <td class="col-width">
                @include('pdf.canvas-section', ['section' => 'costs', 'label' => 'Custos'])
            </td>
        </tr>
    </table>
</body>
</html>

@php
    $treeData = $this->getTreeData();
@endphp

<x-filament-panels::page>
    <style>
        .wbs-container {
            overflow-x: auto;
            min-height: 500px;
            padding: 20px;
            background-color: rgb(249 250 251); 
            border-radius: 0.5rem;
        }

        .dark .wbs-container {
            background-color: rgb(17 24 39);
        }

        /* Tree Structure */
        .tf-tree {
            display: inline-table;
            text-align: center;
        }

        .tf-tree ul {
            padding-top: 20px; 
            position: relative;
            transition: all 0.5s;
            display: flex;
            justify-content: center;
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .tf-tree li {
            float: left; text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;
            transition: all 0.5s;
        }

        /* Connectors */
        .tf-tree li::before, .tf-tree li::after {
            content: '';
            position: absolute; top: 0; right: 50%;
            border-top: 1px solid #ccc;
            width: 50%; height: 20px;
        }
        .tf-tree li::after {
            right: auto; left: 50%;
            border-left: 1px solid #ccc;
        }
        .tf-tree li:only-child::after, .tf-tree li:only-child::before {
            display: none;
        }
        .tf-tree li:only-child { padding-top: 0; }
        .tf-tree li:first-child::before, .tf-tree li:last-child::after {
            border: 0 none;
        }
        .tf-tree li:last-child::before{
            border-right: 1px solid #ccc;
            border-radius: 0 5px 0 0;
        }
        .tf-tree li:first-child::after{
            border-radius: 5px 0 0 0;
        }

        .tf-tree ul ul::before{
            content: '';
            position: absolute; top: 0; left: 50%;
            border-left: 1px solid #ccc;
            width: 0; height: 20px;
        }

        /* Node Styling */
        .tf-nc {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: white;
            padding: 10px;
            display: inline-block;
            transition: all 0.5s;
            position: relative;
            min-width: 150px;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            text-decoration: none;
            color: inherit;
        }

        .dark .tf-nc {
            background-color: rgb(31 41 55);
            border-color: rgb(55 65 81);
        }

        .tf-nc:hover {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        /* Styling the connector lines specifically */
        .tf-tree li::before, 
        .tf-tree li::after, 
        .tf-tree ul ul::before {
            border-color: #9ca3af; 
        }

        /* Content inside nodes */
        .node-title {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 4px;
        }
        .node-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .node-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 6px;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 6px;
        }
    </style>

    <div class="wbs-container">
        <div class="tf-tree">
            <ul>
                <li>
                    <a href="#" class="tf-nc border-l-4 border-l-primary-500">
                        <div class="node-title text-primary-600">{{ $record->name }}</div>
                        <div class="node-meta">Projeto</div>
                        <div class="node-badge bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300">
                            {{ round($record->progress) }}% Concluído
                        </div>
                    </a>
                    
                    @if(count($treeData) > 0)
                        <ul>
                            @foreach($treeData as $node)
                                @include('filament.resources.projects.pages.project-wbs-node', ['node' => $node])
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</x-filament-panels::page>

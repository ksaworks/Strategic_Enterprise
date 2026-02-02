<li>
    <a href="{{ \App\Filament\Resources\Tasks\TaskResource::getUrl('edit', ['record' => $node['id']]) }}" 
       class="tf-nc border-b-2"
       style="border-bottom-color: rgb(var(--{{ $node['status_color'] }}-500));"
       target="_blank">
        
        <div class="node-title">{{ $node['name'] }}</div>
        
        <div class="node-meta space-y-1">
            <div class="flex items-center justify-between text-xs gap-2">
                <span>{{ $node['start_date'] ?? 'N/D' }}</span>
                <span>➔</span>
                <span>{{ $node['end_date'] ?? 'N/D' }}</span>
            </div>
        </div>

        <div class="node-badge" 
             style="background-color: rgb(var(--{{ $node['status_color'] }}-50)); color: rgb(var(--{{ $node['status_color'] }}-700));">
            {{ $node['status'] }} • {{ floatval($node['progress']) }}%
        </div>
    </a>

    @if(count($node['children']) > 0)
        <ul>
            @foreach($node['children'] as $child)
                @include('filament.resources.projects.pages.project-wbs-node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>

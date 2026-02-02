<div class="section-title">{{ $label }}</div>
@if(isset($items[$section]))
    @foreach($items[$section] as $item)
        <div class="item bg-{{ $item->color }}">
            <div class="item-title">{{ $item->title }}</div>
            @if($item->content)
                <div class="item-content">{!! nl2br(e($item->content)) !!}</div>
            @endif
        </div>
    @endforeach
@endif

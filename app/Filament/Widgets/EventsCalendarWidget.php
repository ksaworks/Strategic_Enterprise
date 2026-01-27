<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EventsCalendarWidget extends CalendarWidget
{
    protected static ?int $sort = 2;

    protected function getEvents(FetchInfo $info): Collection|array|Builder
    {
        return Event::query()
            ->whereDate('end_datetime', '>=', $info->start)
            ->whereDate('start_datetime', '<=', $info->end);
    }
}

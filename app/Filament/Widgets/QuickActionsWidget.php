<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 1;

    protected string $view = 'filament.widgets.quick-actions-widget';
}

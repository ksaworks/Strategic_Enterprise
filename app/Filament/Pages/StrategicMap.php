<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use BackedEnum;

class StrategicMap extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected string $view = 'filament.pages.strategic-map';

    protected static ?string $title = 'Mapa Estratégico';

    protected static ?string $navigationLabel = 'Mapa Estratégico';

    protected static ?string $slug = 'strategic-map';

    public function getViewData(): array
    {
        return [
            'perspectives' => \App\Models\Perspective::query()
                ->with(['strategicObjectives' => function($query) {
                    $query->where('is_active', true)
                          ->with(['criticalSuccessFactors' => function($q) {
                              $q->where('is_active', true);
                          }]);
                }])
                ->where('is_active', true)
                ->orderBy('order')
                ->get(),
        ];
    }
}

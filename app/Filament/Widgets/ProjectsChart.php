<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ProjectsChart extends ChartWidget
{
    protected ?string $heading = 'Projects Chart';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $data = \Flowframe\Trend\Trend::model(\App\Models\Project::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Projetos Criados',
                    'data' => $data->map(fn(\Flowframe\Trend\TrendValue $value) => $value->aggregate),
                    'borderColor' => '#d58f05',
                    'backgroundColor' => 'rgba(213, 143, 5, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointBackgroundColor' => '#d58f05',
                    'pointBorderColor' => '#0f1729',
                    'pointBorderWidth' => 2,
                ],
            ],
            'labels' => $data->map(fn(\Flowframe\Trend\TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => '#1f2937',
                    'titleColor' => '#f9fafb',
                    'bodyColor' => '#d1d5db',
                    'borderColor' => '#374151',
                    'borderWidth' => 1,
                    'padding' => 12,
                    'cornerRadius' => 8,
                ],
                'legend' => [
                    'labels' => [
                        'color' => '#9ca3af',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'color' => 'rgba(75, 85, 99, 0.3)',
                    ],
                    'ticks' => [
                        'color' => '#9ca3af',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'color' => 'rgba(75, 85, 99, 0.3)',
                    ],
                    'ticks' => [
                        'color' => '#9ca3af',
                    ],
                ],
            ],
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ];
    }
}


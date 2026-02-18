<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AgentPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Agents';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Sales Volume',
                    'data' => [50, 45, 30, 25, 20],
                    'backgroundColor' => '#f59e0b', // Amber-500
                ],
            ],
            'labels' => ['Agent A', 'Agent B', 'Agent C', 'Agent D', 'Agent E'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

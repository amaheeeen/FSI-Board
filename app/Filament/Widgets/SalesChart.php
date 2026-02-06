<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        // Mock Data for "Sales vs Target"
        return [
            'datasets' => [
                [
                    'label' => 'Monthly Sales',
                    'data' => [100, 200, 150, 300, 250, 400], // Mock data
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
                [
                    'label' => 'Target',
                    'data' => [150, 150, 150, 150, 150, 150],
                    'borderColor' => '#FF6384',
                    'type' => 'line', // Mixed chart
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class SalesTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Trend (Last 12 Months)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Mock data for now, ideally group Transaction sum by month
        // $data = Trend::model(Transaction::class)
        //     ->between(start: now()->subYear(), end: now())
        //     ->perMonth()
        //     ->count();
        
        return [
            'datasets' => [
                [
                    'label' => 'Package Sales',
                    'data' => [100, 150, 120, 200, 180, 250, 300, 280, 350, 400, 450, 500],
                    'borderColor' => '#10b981', // Emerald-500
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

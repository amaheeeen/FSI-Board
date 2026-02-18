<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PackagePopularityChart extends ChartWidget
{
    protected static ?string $heading = 'Most Booked Packages';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => [120, 90, 80, 60, 40],
                    'backgroundColor' => '#34d399', // Emerald-400
                ],
            ],
            'labels' => ['Umrah Ramadhan', 'Umrah Plus Turkey', 'Umrah Reguler', 'Hajj Plus', 'Badal Umrah'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
        ];
    }
}

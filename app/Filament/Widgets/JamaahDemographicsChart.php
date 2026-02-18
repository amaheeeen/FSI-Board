<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class JamaahDemographicsChart extends ChartWidget
{
    protected static ?string $heading = 'Jamaah Demographics';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Mock data
        return [
            'datasets' => [
                [
                    'label' => 'Gender',
                    'data' => [60, 40], // Male, Female
                    'backgroundColor' => ['#10b981', '#fbbf24'], // Emerald, Amber
                ],
            ],
            'labels' => ['Male', 'Female'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}

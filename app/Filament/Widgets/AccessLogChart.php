<?php

namespace App\Filament\Widgets;

use App\Models\AccessLog;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;

class AccessLogChart extends ChartWidget
{
    protected static ?string $heading = 'Access Log - Last 30 Day';

    protected function getData(): array
    {
        // Ambil data jumlah akses per hari 30 hari terakhir
        $data = Trend::model(AccessLog::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Access Count',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('d M')),
        ];
    }

    public function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true, // sumbu y mulai dari 0
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

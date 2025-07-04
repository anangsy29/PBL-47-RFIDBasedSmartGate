<?php

namespace App\Filament\User\Widgets;

use App\Models\AccessLog;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AccessLogChart extends ChartWidget
{
    protected static ?string $heading = 'Your Access Logs This Month';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $userId = auth()->id();

        $data = Trend::query(
            AccessLog::query()
                ->whereHas('rfidTag', fn($q) => $q->where('user_id', $userId))
        )
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->dateColumn('accessed_at')
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Access Count',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
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

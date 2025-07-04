<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';
    protected static ?int $sort = 1;

    public function getViewData(): array
    {
        return [
            'userName' => auth()->user()->name,
            'day' => now()->format('l'),
            'time' => now()->format('g:i A'),
            'date'  => now()->format('F d Y'),
        ];
    }

    protected int | string | array $columnSpan = 'full';
}

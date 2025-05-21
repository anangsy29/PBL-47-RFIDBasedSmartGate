<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';

    public function getViewData(): array
    {
        return [
            'day' => now()->format('l'),
            'time' => now()->format('g:i A'),
            'date'  => now()->format('F d Y'),
        ];
    }

    protected int | string | array $columnSpan = 'full';
}
<x-filament-widgets::widget>
    <x-filament::card class="bg-gray-700 text-white">
        <h2 class="text-2xl font-bold">Welcome, {{ $userName }}!</h2>
        <p class="text-lg mt-2">
            Today is {{ $day }}, at {{ $date }}, {{ $time }}<br>
            Hope you're doing well
        </p>
    </x-filament::card>
</x-filament-widgets::widget>

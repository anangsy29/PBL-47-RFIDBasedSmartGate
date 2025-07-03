<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="pt-2 flex items-center gap-3">
    <x-filament::button type="submit">
        Save Changes
    </x-filament::button>

    <a
        href="{{ route('filament.admin.pages.dashboard') }}"
        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 shadow-sm transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-800"
    >
        Cancel
    </a>
</div>
    </form>
</x-filament::page>

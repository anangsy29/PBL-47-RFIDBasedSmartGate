<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="pt-2 flex items-center gap-3">
            <x-filament::button type="submit" color="primary">
                Save Changes
            </x-filament::button>
            <x-filament::button color="gray" tag="a" href="{{ route('filament.user.pages.dashboard') }}">
                Cancel
            </x-filament::button>
        </div>
    </form>
</x-filament::page>

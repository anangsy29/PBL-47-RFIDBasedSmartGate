<x-filament-panels::page.simple>
    {{-- <x-panel-switcher-button /> --}}

    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <div class="mt-4 text-center text-sm">
        @if(request()->routeIs('filament.admin.auth.login'))
            <a href="{{ route('filament.user.auth.login') }}" class="text-primary-600 hover:underline dark:text-primary-400">
                Go to User Panel
            </a>
        @elseif(request()->routeIs('filament.user.auth.login'))
            <a href="{{ route('filament.admin.auth.login') }}" class="text-primary-600 hover:underline dark:text-primary-400">
                Go to Admin Panel
            </a>
        @endif
    </div>
    
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>

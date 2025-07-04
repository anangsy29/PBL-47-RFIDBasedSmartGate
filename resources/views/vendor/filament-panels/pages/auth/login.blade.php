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

    @if(request()->routeIs('filament.admin.auth.login'))
        <div class="mt-4 text-center text-sm">
            <a href="{{ route('filament.user.auth.login') }}" class="text-primary-600 hover:underline dark:text-primary-400">
                Go to User Panel
            </a>
        </div>
    @endif

    @if(request()->routeIs('filament.user.auth.login'))
    <div class="mt-4 text-center text-sm">
        <button 
            onclick="document.getElementById('adminCodeModal').classList.remove('hidden')"
            class="text-primary-600 hover:underline dark:text-primary-400"
        >
            Go to Admin Panel
        </button>
    </div>

    <!-- Modal -->
    <div id="adminCodeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Enter Admin Code</h2>
            <input 
                id="adminCodeInput"
                type="password"
                placeholder="Enter admin code"
                class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-primary-500 text-black dark:text-white dark:bg-gray-600"/>
            <div class="flex justify-end space-x-2 gap-3">
                <button 
                    onclick="checkAdminCode()"
                    class="px-4 py-2 rounded bg-primary-600 text-white hover:bg-primary-700">
                    Submit
                </button>
                <button 
                    onclick="document.getElementById('adminCodeModal').classList.add('hidden')"
                    class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cancel
                </button>
            </div>
            <p id="adminCodeError" class="text-red-600 mt-2 hidden">Incorrect code, please try again.</p>
        </div>
    </div>

    <script>
        function checkAdminCode() {
            const code = document.getElementById('adminCodeInput').value;
            const error = document.getElementById('adminCodeError');
            const modal = document.getElementById('adminCodeModal');

            // Password bisa disesuaikan
            if (code === 'admin123') {
                window.location.href = "{{ route('filament.admin.auth.login') }}";
            } else {
                error.classList.remove('hidden');
            }
        }
    </script>
@endif


    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>

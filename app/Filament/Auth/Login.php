<?php

namespace App\Filament\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
        ]);
    }

    protected function attemptAuthentication(): void
    {
        $credentials = $this->form->getState();

        if (!Auth::guard('web')->attempt($credentials, $this->hasRemember())) {
            throw ValidationException::withMessages([
                'email' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        session()->regenerate();
    }

    public function getRedirectUrl(): string
    {
        // Arahkan ke halaman setelah login berhasil
        return route('filament.user.pages.dashboard');
    }
}

<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.edit-profile';
    protected static ?string $navigationLabel = 'Profile';
    protected static ?string $title = 'Profile';
    protected static ?string $navigationGroup = 'Account';
    protected static ?int $navigationSort = -1;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Profile Information')
                ->description('Update your account\'s profile information.')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->label('Name')
                                ->required(),

                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->disabled(),
                        ]),
                ]),
            Section::make('Update Password')
                ->description('Leave blank if you do not want to change your password.')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('password')
                                ->label('New Password')
                                ->password()
                                ->revealable()
                                ->minLength(8)
                                ->nullable(),

                            TextInput::make('password_confirmation')
                                ->label('Confirm New Password')
                                ->password()
                                ->revealable()
                                ->same('password')
                                ->nullable(),
                        ]),
                ]),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();
        $user->name = $data['name'];

        $passwordUpdated = false;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $passwordUpdated = true;
        }

        $user->save();

        Notification::make()
            ->title('Profile updated successfully.')
            ->success()
            ->send();

        if ($passwordUpdated) {
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            Notification::make()
                ->title('Password updated.')
                ->body('Please login again.')
                ->success()
                ->send();

            redirect('/admin/login')->with('status', 'Password updated. Please login again.');
        }
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Button::make('Save')
                ->submit('submit')
                ->label('Save Changes')
                ->button(),
        ];
    }
}

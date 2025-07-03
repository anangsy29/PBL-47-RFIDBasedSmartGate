<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.edit-profile-user';
    protected static ?string $navigationLabel = 'Profile';
    protected static ?string $title = 'Profile';
    protected static ?string $navigationGroup = 'Account';
    protected static ?int $navigationSort = -1;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $address;
    public $phone_number;

    public function mount(): void
    {
        $user = auth()->user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'phone_number' => $user->phone_number,
        ]);
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

                            TextInput::make('address')
                                ->label('Address')
                                ->nullable(),

                            TextInput::make('phone_number')
                                ->label('Phone Number')
                                ->tel()
                                ->nullable(),
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

    public function submit()
    {
        $data = $this->form->getState();

        $user = auth()->user();
        $user->name = $data['name'];
        $user->address = $data['address'] ?? null;
        $user->phone_number = $data['phone_number'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        Notification::make()
            ->title('Profile updated successfully.')
            ->success()
            ->send();

        //logout if password changed
        if (!empty($data['password'])) {
            auth()->logout();
            return redirect('/user/login')->with('status', 'Password updated. Please login again.');
        }
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Button::make('Save')
                ->submit('submit')
                ->label('Save Changes')
                ->button()
                ->color('primary'),

            Forms\Components\Actions\Action::make('Cancel')
                ->label('Cancel')
                ->url(route('filament.user.pages.dashboard'))
                ->color('gray'),
        ];
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Officer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeOfficerUser extends Command
{
    protected $signature = 'make:filament-officer';

    protected $description = 'Create a new Filament admin officer account';

    public function handle()
    {
        $name = $this->ask('Officer Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password');

        if (Officer::where('email', $email)->exists()) {
            $this->error('An officer with this email already exists.');
            return;
        }

        Officer::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Officer account created successfully!');
    }
}

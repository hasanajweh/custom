<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateSuperAdmin extends Command
{
    protected $signature = 'app:create-super-admin';
    protected $description = 'Create a new Super Admin user';

    public function handle()
    {
        $name = $this->ask('Full Name?');
        $email = $this->ask('Email Address?');
        $password = $this->secret('Password?');
        $confirmPassword = $this->secret('Confirm Password?');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $confirmPassword,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            $this->info('Super Admin not created. See error messages below:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'school_id' => null, // This is the key difference
            'is_super_admin' => true,
        ]);

        $this->info('Super Admin created successfully.');
        return 0;
    }
}

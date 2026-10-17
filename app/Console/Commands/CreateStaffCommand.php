<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateStaffCommand extends Command
{
    protected $signature = 'staff:create {--role=receptionist : Staff role (admin or receptionist)}';

    protected $description = 'Create a staff account using private interactive prompts';

    public function handle(): int
    {
        $role = $this->option('role');

        if (! in_array($role, [User::ROLE_ADMIN, User::ROLE_RECEPTIONIST], true)) {
            $this->components->error('Role must be admin or receptionist.');

            return self::FAILURE;
        }

        $name = trim($this->ask('Staff name', ''));
        $email = strtolower(trim($this->ask('Staff email', '')));
        $password = $this->secret('Password');
        $passwordConfirmation = $this->secret('Confirm password');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        $this->components->info("Created {$role} account for {$email}.");

        return self::SUCCESS;
    }
}

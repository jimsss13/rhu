<?php

namespace App\Console\Commands;

use App\Models\RhuUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'rhu:create-admin';

    protected $description = 'Create an active IT administrator for a new RHU installation';

    public function handle(): int
    {
        $attributes = [
            'f_name' => $this->ask('First name'),
            'l_name' => $this->ask('Last name'),
            'username' => $this->ask('Username'),
            'email' => $this->ask('Email'),
            'password' => $this->secret('Password'),
            'password_confirmation' => $this->secret('Confirm password'),
        ];

        $validator = Validator::make($attributes, [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:rhu_user,username',
            'email' => 'required|email|max:254|unique:rhu_user,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        RhuUser::create(array_merge($validator->validated(), [
            'department' => 'IT DEPARTMENT', 'role' => 'Admin', 'status' => 'Active',
        ]));

        $this->info('IT administrator created. You can now sign in.');

        return self::SUCCESS;
    }
}

<?php

namespace Database\Factories;

use App\Models\RhuUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class RhuUserFactory extends Factory
{
    protected $model = RhuUser::class;

    public function definition(): array
    {
        // Assign a random role
        $role = $this->faker->randomElement(['Client', 'Admin']);

        // Assign department based on role
        $department = $role === 'Client' ? 'Client' : $this->faker->randomElement([
            'IT DEPARTMENT',
            'SUPER ADMIN',
            'CONSULTATION',
            'VACCINATION',
            'LABORATORY',
            'INVENTORY',
            'BLOOD',
        ]);

        // Assign status based on role
        $status = $role === 'Client' ? 'Pending' : 'Active';

        return [
            'f_name' => $this->faker->firstName,
            'm_name' => $this->faker->optional()->firstName,
            'l_name' => $this->faker->lastName,
            'suffix' => $this->faker->optional()->suffix,
            'bday' => $this->faker->date,
            'contactNo' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'email' => $this->faker->unique()->safeEmail,
            'street' => $this->faker->streetAddress,
            'brgy' => $this->faker->streetName,
            'municipality' => $this->faker->city,
            'province' => $this->faker->state,
            'zip_code' => $this->faker->postcode,
            'status' => $status,
            'role' => $role,
            'department' => $department,
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'),
        ];
    }
}

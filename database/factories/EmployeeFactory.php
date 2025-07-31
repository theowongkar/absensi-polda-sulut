<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'nrp' => fake()->unique()->numerify('#########'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'position' => fake()->jobTitle(),
            'date_of_birth' => fake()->date(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'avatar' => null,
            'status' => fake()->randomElement(['Aktif', 'Pensiun', 'Meninggal Dunia', 'Diberhentikan']),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::inRandomOrder()->first()?->id,
            'date' => fake()->unique()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'check_in' => fake()->time('H:i:s', '09:00:00'),
            'check_out' => fake()->time('H:i:s', '17:00:00'),
            'photo_check_in' => null,
            'photo_check_out' => null,
            'status' => fake()->randomElement(['Hadir', 'Terlambat', 'Sakit', 'Tidak Hadir']),
        ];
    }
}

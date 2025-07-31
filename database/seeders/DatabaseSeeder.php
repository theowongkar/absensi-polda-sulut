<?php

namespace Database\Seeders;

use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Admin
        $adminUser = User::factory()->create([
            'name' => 'Admin Sistem',
            'email' => 'admin@poldasulut.com',
            'role' => 'Admin',
        ]);

        $adminEmployee = Employee::factory()->create([
            'user_id' => $adminUser->id,
            'name' => 'Theoterra Wongkar',
            'gender' => 'Laki-laki',
            'position' => 'Administrator',
            'date_of_birth' => '2003-08-19',
            'address' => 'Kali Selatan',
            'phone' => '082158889973',
            'status' => 'Aktif',
        ]);

        // Absensi Admin
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            Attendance::create([
                'employee_id' => $adminEmployee->id,
                'date' => $date,
                'check_in' => '08:00:00',
                'check_out' => '17:00:00',
                'status' => 'Hadir',
            ]);
        }

        // 4 User biasa dengan employee + attendance
        for ($u = 1; $u <= 4; $u++) {
            $user = User::factory()->create([
                'role' => 'Pengguna',
            ]);

            $employee = Employee::factory()->create([
                'user_id' => $user->id,
                'status' => 'Aktif',
            ]);

            // Attendance untuk masing-masing user (7 hari terakhir)
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');

                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date,
                    'check_in' => '08:' . rand(0, 5) . '0:00',
                    'check_out' => '17:' . rand(0, 5) . '0:00',
                    'status' => fake()->randomElement(['Hadir', 'Terlambat', 'Sakit', 'Tidak Hadir']),
                ]);
            }
        }
    }
}

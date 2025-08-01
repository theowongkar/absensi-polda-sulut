<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
    }
}

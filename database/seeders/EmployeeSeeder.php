<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Divisions;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = Divisions::all();

        Employee::create([
            'name' => 'Faruq Al Fahmi',
            'phone' => '081232032649',
            'position' => 'Developer',
            'image' => 'https://i.pinimg.com/originals/96/fb/22/96fb22afbbca68f3b8ae8289b6282168.jpg',
            'division_id' => $divisions->random()->id,
        ]);

        Employee::create([
            'name' => 'Fahmi Al Faruq',
            'phone' => '081234567891',
            'position' => 'Designer',
            'image' => 'https://i.pinimg.com/564x/2b/05/48/2b054882609bbaf6728aca0368212c14.jpg',
            'division_id' => $divisions->random()->id,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Divisions;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            'Mobile Apps', 
            'QA', 
            'Full Stack', 
            'Backend', 
            'Frontend', 
            'UI/UX Designer'
        ];

        foreach ($divisions as $division) {
            Divisions::create([
                'id' => Str::uuid(),
                'name' => $division,
            ]);
        }
    }
}

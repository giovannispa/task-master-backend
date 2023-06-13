<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'name' => "TechLead",
            'color' => "#FFFFFF",
            'active' => 1,
        ]);

        DB::table('categories')->insert([
            'name' => "Developer",
            'color' => "#FFFFFF",
            'active' => 1,
        ]);
    }
}

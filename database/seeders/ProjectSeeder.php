<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            'status_id' => 1,
            'team_id' => 1,
            'name' => "Teste projeto",
            'description' => "Projeto de teste",
            'start_date' => date("Y-m-d"),
            'deadline' => date("Y-m-d"),
            'end_date' => date("Y-m-d"),
        ]);
    }
}

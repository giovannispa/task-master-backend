<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            'name' => 'Testando time',
            'leader_id' => 1,
            'active_projects' => 1,
            'worked_projects' => 2,
            'tasks_performed' => 30
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            'project_id' => 1,
            'status_id' => 1,
            'priority_id' => 1,
            'created_by' => 1,
            'assigned_to' => 2,
            'name' => "Teste de task",
            'description' => "Teste de task",
            'start_date' => date("Y-m-d"),
            'end_date' => date("Y-m-d")
        ]);
    }
}

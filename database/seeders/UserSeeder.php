<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'category_id' => 1,
            'name' => "Giovanni Sertorio",
            'email' => "giovanni@gmail.com",
            'password' => bcrypt("teste"),
            'worked_projects' => 0,
            'tasks_performed' => 0,
            'active' => 1
        ]);

        DB::table('users')->insert([
            'category_id' => 2,
            'name' => "Pedro Henrique",
            'email' => "pedro@gmail.com",
            'password' => bcrypt("teste"),
            'worked_projects' => 0,
            'tasks_performed' => 0,
            'active' => 1
        ]);
    }
}

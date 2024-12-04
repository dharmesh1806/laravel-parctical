<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'firstName' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => bcrypt('password123'),
            ],
            [
                'firstName' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'password' => bcrypt('password456'),
            ],
        ]);

        DB::table('roles')->insert([
            'role' => 'test role',
        ]);
    
    }
}

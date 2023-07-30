<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John',
                'email' => 'john@example.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'qa',
            ],
            [
                'name' => 'David',
                'email' => 'david@example.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'glp',
            ],
            [
                'name' => 'Rahul',
                'email' => 'rahul@example.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'finance',
            ],
            // Add more users as needed
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

         User::create([
             'image' => 'Test User',
             'name' => 'Fleksis',
             'email' => 'asd@asd.com',
             'password' => Hash::make('admin123'),
         ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

        Storage::disk('local')->makeDirectory('public/'.'1'.'/'.'TESTA_MAPĪTE_AR_FAILIEM');
        Folder::create([
            'title' => 'TESTA_MAPĪTE_AR_FAILIEM',
            'user_id' => 1,
            'folder_location' => 'public/'.'1'.'/'.'TESTA_MAPĪTE_AR_FAILIEM',
        ]);
    }
}

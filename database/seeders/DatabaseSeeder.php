<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $mainAdmin = Role::create(['name' => 'Main Administrator']);
        $user = Role::create(['name' => 'User']);
        $fakeUser = Role::create(['name' => 'Fake User']);
        $guest = Role::create(['name' => 'Guest']);

        Permission::create(['name' => 'index.users'])->assignRole($mainAdmin);
        Permission::create(['name' => 'delete.users'])->assignRole($mainAdmin);

        Permission::create(['name' => 'index.supports'])->assignRole($mainAdmin);
        Permission::create(['name' => 'create.supports'])->assignRole($mainAdmin);
        Permission::create(['name' => 'update.supports'])->assignRole($mainAdmin);
        Permission::create(['name' => 'delete.supports'])->assignRole($mainAdmin);

        Permission::create(['name' => 'index.topics'])->assignRole($mainAdmin);
        Permission::create(['name' => 'create.topics'])->assignRole($mainAdmin);
        Permission::create(['name' => 'update.topics'])->assignRole($mainAdmin);
        Permission::create(['name' => 'delete.topics'])->assignRole($mainAdmin);

        Permission::create(['name' => 'userFiles'])->assignRole([$user, $fakeUser]);
        Permission::create(['name' => 'userFolders'])->assignRole([$user, $fakeUser]);
        Permission::create(['name' => 'me'])->assignRole([$user, $fakeUser]);
        Permission::create(['name' => 'logout'])->assignRole([$user, $fakeUser]);

        User::create([
           'image' => 'Test User',
           'name' => 'Fleksis',
           'email' => 'asd@asd.com',
           'password' => Hash::make('admin123'),
        ])->assignRole($mainAdmin);

        Storage::disk('local')->makeDirectory('public/'.'1'.'/'.'TESTA_MAPĪTE_AR_FAILIEM');
        Folder::create([
            'title' => 'TESTA_MAPĪTE_AR_FAILIEM',
            'user_id' => 1,
            'folder_location' => 'public/'.'1'.'/'.'TESTA_MAPĪTE_AR_FAILIEM',
        ]);
        User::factory()->times(20)->create()->each(function ($factoryUser) {
            $factoryUser->assignRole('Fake User');
        });
    }
}

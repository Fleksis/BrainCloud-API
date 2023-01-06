<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\File;
use App\Models\Folder;
use App\Models\Subscription;
use App\Models\Topic;
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
        Permission::create(['name' => 'destroy.users'])->assignRole($mainAdmin);

        Permission::create(['name' => 'subscriptions'])->assignRole($mainAdmin);

        Permission::create(['name' => 'supports'])->assignRole($mainAdmin);

        Permission::create(['name' => 'topics'])->assignRole($mainAdmin);

        Permission::create(['name' => 'userFiles'])->assignRole([$user, $fakeUser]);
        Permission::create(['name' => 'userFolders'])->assignRole([$user, $fakeUser]);
        Permission::create(['name' => 'me'])->assignRole([$user, $fakeUser]);
        Permission::create(['name' => 'logout'])->assignRole([$user, $fakeUser]);

        $subscriptions = array(
            ['type' => 'Free', 'max_space' => 20],
            ['type' => 'Basic', 'max_space' => 50],
            ['type' => 'Extra', 'max_space' => 100],
            ['type' => 'Rugged storage', 'max_space' => 500],
        );
        foreach ($subscriptions as $subscription) {
            Subscription::create([
                'type' => $subscription['type'],
                'max_space' => $subscription['max_space'],
            ]);
        }

        User::create([
           'image' => 'Test User',
           'name' => 'Main administrator',
//           'email' => 'markuss0303@gmail.com',
            'email' => 'asd@asd.com',
           'password' => Hash::make('admin123'),
        ])->assignRole($mainAdmin);
        Storage::disk('local')->makeDirectory('public/'.'1'.'/'.'TEST_FOLDER_FOR_ADMIN');
        Folder::create([
            'title' => 'TEST_FOLDER_FOR_ADMIN',
            'user_id' => 1,
            'folder_location' => 'public/'.'1'.'/'.'TEST_FOLDER_FOR_ADMIN',
        ]);

        User::create([
            'image' => 'Test User',
            'name' => 'User without admin rights',
            'email' => 'latvian10@gmail.com',
            'password' => Hash::make('admin123'),
        ])->assignRole($user);
        Storage::disk('local')->makeDirectory('public/'.'2'.'/'.'TEST_FOLDER_FOR_USER');
        Folder::create([
            'title' => 'TEST_FOLDER_FOR_USER',
            'user_id' => 2,
            'folder_location' => 'public/'.'2'.'/'.'TEST_FOLDER_FOR_USER',
        ]);

        User::factory()->times(20)->create()->each(function ($factoryUser) {
            $factoryUser->assignRole('Fake User');
        });

        $topics = [
            'Account issues',
            'Folder issues',
            'File issues',
            'Report a bug',
            'Other',
        ];
        foreach ($topics as $topic) {
            Topic::create([
                'title' => $topic,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserSettings;
use Database\Factories\UserPreferencesFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $root = User::factory()->create([
            'name' => 'root',
            'email' => 'root@example.com',
            'password' => Hash::make('adminadmin'),
        ]);
        UserSettings::factory()->create([
            'user_id' => $root->id,
            'locale' => 'bs',
            'date_format' => 'd.m.Y',
            'time_format' => 'H:i',
        ]);


        $users = User::factory()->count(50)->create();
        $users->each(function ($user) {
            UserSettings::factory()->create(['user_id' => $user->id]);
        });

        $postCategories = [
            ['name' => 'News'],
            ['name' => 'Technology'],
            ['name' => 'Design'],
            ['name' => 'Education'],
        ];
        foreach ($postCategories as $category) {
            PostCategory::create($category);
        }

        Post::factory()->count(50)->create();

    }
}

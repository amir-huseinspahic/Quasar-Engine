<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserPreferences;
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
        UserPreferences::factory()->create([
            'user_id' => $root->id,
            'date_format' => 'd.m.Y'
        ]);


        $users = User::factory()->count(50)->create();
        $users->each(function ($user) {
            UserPreferences::factory()->create(['user_id' => $user->id]);
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

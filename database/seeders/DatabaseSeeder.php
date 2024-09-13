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
use Laravolt\Avatar\Facade as Avatar;

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
            'password' => Hash::make('root'),
            'is_hidden' => true
        ]);
        UserSettings::factory()->create([
            'user_id' => $root->id,
            'locale' => 'bs',
            'timezone' => 'Europe/Sarajevo',
            'date_format' => 'DD.MM.YYYY',
            'time_format' => 'HH:mm:ss',
        ]);
        $root->assignRole('root');
        Avatar::create($root->name)->save(public_path('media/users/avatars/' . $root->id . '.png'));

        $users = User::factory()->count(50)->create();
        $users->each(function ($user) {
            UserSettings::factory()->create(['user_id' => $user->id]);
            Avatar::create($user->name)->save(public_path('media/users/avatars/' . $user->id . '.png'));
            $user->assignRole('writer');
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

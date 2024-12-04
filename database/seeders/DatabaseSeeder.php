<?php

namespace Database\Seeders;

use App\Models\AppSettings;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserSettings;
use Database\Factories\UserPreferencesFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravolt\Avatar\Facade as Avatar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        AppSettings::factory()->create();

//        $root = User::factory()->create([
//            'name' => 'root',
//            'email' => 'root@example.com',
//            'password' => Hash::make('root'),
//            'is_hidden' => true
//        ]);
//        UserSettings::factory()->create([
//            'user_id' => $root->id,
//            'locale' => 'en',
//            'timezone' => 'Europe/London',
//            'date_format' => 'DD.MM.YYYY',
//            'time_format' => 'HH:mm:ss',
//        ]);
//        $root->assignRole('root');
//        Avatar::create($root->name)->save(public_path('media/users/avatars/' . $root->id . '.png'));

        $users = User::factory()->count(6)->create();
        $users->each(function ($user) {
            UserSettings::factory()->create(['user_id' => $user->id]);
            $user->password = Hash::make(Str::random(8));
            $user->assignRole('writer');
            Avatar::create($user->name)->save(public_path('media/users/avatars/' . $user->id . '.png'));
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

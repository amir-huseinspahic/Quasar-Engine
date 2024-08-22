<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $users = User::all('id');
        $postCategories = PostCategory::all('id');

        return [
            'user_id' => rand(2, $users->count()), // The reason the rand function is limited to 2 and not 1 is so the root account doesn't get any posts assigned. The root account is created first with the seeder.
            'post_category_id' => rand(1, $postCategories->count()),
            'title' => $this->faker->sentence(rand(3, 10)),
            'slug' => $this->faker->slug(),
            'forewords' => $this->faker->paragraph(rand(3, 10)),
            'content' => $this->faker->paragraphs(rand(2, 10), true),
            'published' => $this->faker->boolean(),
            'thumbnail' => $this->faker->image(public_path() . '/media/posts/thumbnails', rand(50, 500), rand(50, 500), null, false),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSettings>
 */
class UserSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'locale' => 'en',
            'timezone' => $this->faker->timezone(),
            'items_per_page' => $this->faker->randomElement([ 5, 10, 25, 50, 100 ]),
            'page_layout' => $this->faker->randomElement(['table', 'cards']),
            'date_format' => $this->faker->randomElement(['DD-MM-YYYY', 'YYYY-MM-DD', 'MM-DD-YYYY']),
            'time_format' => $this->faker->randomElement(['HH:mm:ss', 'h:mm:ss A']),
            'show_private_posts' => $this->faker->boolean(),
        ];
    }
}

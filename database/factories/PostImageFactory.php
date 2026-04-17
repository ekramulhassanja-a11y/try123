<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostImage>
 */
class PostImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $randomId = fake()->numberBetween(1, 1000);

        return [
            'post_id' => Post::inRandomOrder()->value('id') ?? Post::factory(),
            'image' => "https://picsum.photos/640/480?random={$randomId}",
        ];
    }
}

<?php

namespace Novius\LaravelFilamentNews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Novius\LaravelFilamentNews\Models\NewsPost;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<NewsPost>
 */
class NewsPostFactory extends Factory
{
    /**
     * {@inheritdoc}
     */
    protected $model = NewsPost::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'locale' => 'en',
            'featured' => false,
            'intro' => $this->faker->paragraph(2),
            'content' => $this->faker->paragraph(5, true),
        ];
    }
}

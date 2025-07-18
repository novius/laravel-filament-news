<?php

namespace Novius\LaravelFilamentNews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Novius\LaravelFilamentNews\Models\NewsTag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<NewsTag>
 */
class NewsTagFactory extends Factory
{
    /**
     * {@inheritdoc}
     */
    protected $model = NewsTag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug(1),
            'locale' => 'en',
        ];
    }
}

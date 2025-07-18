<?php

namespace Novius\LaravelFilamentNews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Novius\LaravelFilamentNews\Models\NewsCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<NewsCategory>
 */
class NewsCategoryFactory extends Factory
{
    /**
     * {@inheritdoc}
     */
    protected $model = NewsCategory::class;

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

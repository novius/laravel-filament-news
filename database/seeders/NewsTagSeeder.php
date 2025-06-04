<?php

namespace Novius\LaravelFilamentNews\Database\Seeders;

use Illuminate\Database\Seeder;
use Novius\LaravelFilamentNews\Models\NewsTag;

class NewsTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsTag::factory()->count(10)->create();
    }
}

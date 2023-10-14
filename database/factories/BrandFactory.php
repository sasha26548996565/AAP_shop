<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'thumbnail' => $this->faker->image('storage/app/public', 100, 100, null, false),
        ];
    }
}

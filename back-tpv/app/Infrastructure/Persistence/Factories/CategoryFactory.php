<?php

namespace App\Infrastructure\Persistence\factories;

use App\Domain\Product\Entities\Category;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Product\Entities\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {

        return [
            'categoria' => $this->faker->word,
            'activo' => $this->faker->boolean,
            'id_empresa' => \App\Domain\Company\Entities\Company::factory()->create()->id,
        ];

    }
}

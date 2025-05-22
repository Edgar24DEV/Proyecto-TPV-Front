<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Product\Entities\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word,
            'precio' => $this->faker->randomFloat(2, 1, 100),
            'imagen' => $this->faker->imageUrl(),
            'activo' => $this->faker->boolean,
            'iva' => $this->faker->randomFloat(2, 0, 21),
            'id_categoria' => \App\Domain\Product\Entities\Category::factory()->create()->id,
            'id_empresa' => \App\Domain\Company\Entities\Company::factory()->create()->id,
        ];
    }
}


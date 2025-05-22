<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Restaurant\Entities\Table;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    /**
     * Define the default state of the table.
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    public function definition(): array
    {
        return [
            'mesa' => $this->faker->word,
            'activo' => $this->faker->boolean,
            'id_ubicacion' => \App\Domain\Restaurant\Entities\Location::factory()->create()->id,
        ];
    }
}


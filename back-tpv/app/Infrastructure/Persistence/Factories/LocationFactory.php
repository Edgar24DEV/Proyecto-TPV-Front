<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Restaurant\Entities\Location;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * Define the default state of the location.
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    public function definition(): array
    {
        return [
            'ubicacion' => $this->faker->word,
            'activo' => $this->faker->boolean,
            'id_restaurante' => \App\Domain\Restaurant\Entities\Restaurant::factory()->create()->id,
        ];
    }
}

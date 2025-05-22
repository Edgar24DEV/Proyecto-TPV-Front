<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Order\Entities\Order;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the default state of the order.
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    public function definition(): array
    {
        return [
            'estado' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
            'fecha_inicio' => $this->faker->dateTime,
            'fecha_fin' => $this->faker->dateTime,
            'comensales' => $this->faker->numberBetween(1, 10),
            'id_mesa' => \App\Domain\Restaurant\Entities\Table::factory()->create()->id,
        ];
    }
}


<?php


namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Order\Entities\OrderLine;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderLineFactory extends Factory
{

    public function definition(): array
    {
        return [
            'cantidad' => $this->faker->numberBetween(1, 10),
            'precio' => $this->faker->randomFloat(2, 1, 100),
            'nombre' => $this->faker->word,
            'observaciones' => $this->faker->sentence,
            'estado' => $this->faker->randomElement(['pendiente', 'entregado', 'cancelado']),
            'id_pedido' => \App\Domain\Order\Entities\Order::factory()->create()->id,
            'id_producto' => \App\Domain\Product\Entities\Product::factory()->create()->id,
        ];
    }
}

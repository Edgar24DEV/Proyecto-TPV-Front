<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Payment\Entities\Payment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{

    public function definition(): array
    {
        return [
            'tipo' => $this->faker->word,
            'cantidad' => $this->faker->randomFloat(2, 1, 1000),
            'fecha' => $this->faker->date(),
            'id_pedido' => \App\Domain\Order\Entities\Order::factory()->create()->id,
            'id_cliente' => \App\Domain\Company\Entities\Client::factory()->create()->id,
            'razon_social' => $this->faker->company,
            'CIF' => $this->faker->bothify('??-######-A'),
            'n_factura' => $this->faker->uuid,
            'correo' => $this->faker->email,
            'direccion_fiscal' => $this->faker->address,
        ];
    }
}


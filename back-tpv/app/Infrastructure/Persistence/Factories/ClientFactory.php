<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Client\Entities\Client;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{

    public function definition(): array
    {
        $empresaFactory = new CompanyFactory();
        $empresa = $empresaFactory->create();
        return [
            'razon_social' => $this->faker->company,
            'cif' => $this->faker->bothify('??-######-A'),
            'direccion_fiscal' => $this->faker->address,
            'correo' => $this->faker->email,
            'id_empresa' => $empresa,
        ];
    }
}


<?php

// app/Infrastructure/Persistence/Factories/RestaurantFactory.php
namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Restaurant\Entities\Restaurant;
use Faker\Factory as FakerFactory;

class RestaurantFactory
{
    public function definition(array $attributes = []): array
    {
        $faker = FakerFactory::create('es_ES');
        return [
            'id' => $faker->unique()->numberBetween(1, 1000),
            'nombre' => $faker->company,
            'direccion' => $faker->address,
            'telefono' => $faker->phoneNumber,
            'contrasenya' => $faker->password,
            'direccion_fiscal' => $faker->address,
            'CIF' => $faker->bothify('??-######-A'),
            'razon_social' => $faker->company,
            'id_empresa' => $attributes['empresa']->getId(), // Pasamos la empresa asociada
        ];
    }

    public function create(array $attributes = []): Restaurant
    {
        $data = $this->definition($attributes);
        return new Restaurant(
            $data['id'],
            $data['nombre'],
            $data['direccion'],
            $data['telefono'],
            $data['contrasenya'],
            $data['direccion_fiscal'],
            $data['CIF'],
            $data['razon_social'],
            $data['id_empresa']
        );
    }
}

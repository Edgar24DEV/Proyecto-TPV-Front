<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Company\Entities\Company;
use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory
{

    public function definition(): array
    {
        $faker = FakerFactory::create('es_ES');
        return [
            'id' => $faker->unique()->numberBetween(1, 1000),
            'nombre' => $faker->name,
            'direccion_fiscal' => $faker->address,
            'CIF' => $faker->bothify('??-######-A'),
            'razon_social' => $faker->company,
            'telefono' => $faker->phoneNumber,
            'correo' => $faker->email,
            'contrasenya' => $faker->password,
        ];
    }

    public function create(array $attributes = []): Company
    {
        $data = $this->definition();

        // Asegúrate de pasar todos los parámetros requeridos al constructor de Company
        return new Company(
            $data['id'],
            $data['nombre'],
            $data['direccion_fiscal'],
            $data['CIF'],
            $data['razon_social'],
            $data['telefono'],
            $data['correo'],
            $data['contrasenya']
        );
    }
}

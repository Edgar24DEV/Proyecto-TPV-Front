<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Employee\Entities\Employee;
use Faker\Factory as FakerFactory;

class EmployeeFactory
{
    public function definition(array $attributes = []): array
    {


        $faker = FakerFactory::create('es_ES');
        return [
            'id' => $faker->unique()->numberBetween(1, 1000),
            'nombre' => $faker->name,
            'pin' => $faker->randomNumber(4),
            'id_empresa' => $attributes['id_empresa'] ?? 1, // Aquí se pasa el id de la empresa
            'id_rol' => $faker->numberBetween(1, 10),
        ];
    }

    public function create(array $attributes = []): Employee
    {
        $data = $this->definition($attributes);
        return new Employee(
            $data['id'],
            $data['nombre'],
            $data['id_rol'],
            $data['pin'],
            $data['id_empresa']
        );
    }
}
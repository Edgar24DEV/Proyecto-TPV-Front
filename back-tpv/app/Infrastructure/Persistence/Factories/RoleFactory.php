<?php

namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Employee\Entities\Role;
use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory
{

    public function definition(): array
    {
        $faker = FakerFactory::create('es_ES');
        return [
            'id' => $faker->unique()->numberBetween(1, 1000),
            'rol' => $faker->word,
            'productos' => $faker->boolean,
            'categorias' => $faker->boolean,
            'tpv' => $faker->boolean,
            'usuarios' => $faker->boolean,
            'mesas' => $faker->boolean,
            'restaurante' => $faker->boolean,
            'clientes' => $faker->boolean,
            'empresa' => $faker->boolean,
            'pago' => $faker->boolean,
            'id_empresa' => new CompanyFactory()->create()->getId(),
        ];
    }

    public function create(array $attributes = []): Role
    {
        $data = $this->definition();
        return new Role(
            $data['id'],
            $data['rol'],
            $data['productos'],
            $data['categorias'],
            $data['tpv'],
            $data['usuarios'],
            $data['mesas'],
            $data['restaurante'],
            $data['clientes'],
            $data['empresa'],
            $data['pago'],
            $data['id_empresa'],
        ); // Instancia la entidad con los datos generados
    }
}


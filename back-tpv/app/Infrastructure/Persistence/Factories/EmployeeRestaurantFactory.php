<?php
// app/Infrastructure/Persistence/Factories/EmployeeRestaurantFactory.php
namespace App\Infrastructure\Persistence\Factories;

use App\Domain\Employee\Entities\EmployeeRestaurant;
use Faker\Factory as FakerFactory;

class EmployeeRestaurantFactory
{
    public function definition(array $attributes = []): array
    {
        $faker = FakerFactory::create('es_ES');
        return [
            "id" => $faker->unique()->numberBetween(1, 1000),
            'id_empleado' => $attributes['id_empleado'], // Usamos el ID del empleado pasado
            'id_restaurante' => $attributes['id_restaurante'], // Usamos el ID del restaurante pasado
        ];
    }

    public function create(array $attributes = []): EmployeeRestaurant
    {
        $data = $this->definition($attributes);
        return new EmployeeRestaurant(
            $data['id'],
            $data['id_empleado'],
            $data['id_restaurante']
        );
    }
}

<?php

namespace App\Domain\Restaurant\Repositories;

use App\Domain\Restaurant\Entities\Location;
use App\Application\Location\DTO\AddLocationCommand;
use App\Application\Table\DTO\AddTableCommand;
use App\Domain\Table\Entities\Table;
use Illuminate\Support\Collection;

interface LocationRepositoryInterface
{
    public function exist(int $idEmpleado): bool;
    public function find(int $restaurant_id): array;
    public function existLocationRestaurant(string $ubicacion, int $idRestaurante): bool;
    public function existLocationRestaurantByID(int $idUbicacion, int $idRestaurante): bool;
    public function create(AddLocationCommand $command): Location;
    public function delete(int $id): bool;
    public function findById(int $id): Location;
}

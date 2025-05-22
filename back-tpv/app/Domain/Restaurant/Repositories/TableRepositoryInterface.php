<?php

namespace App\Domain\Restaurant\Repositories;
use App\Application\Table\DTO\AddTableCommand;
use App\Application\Table\DTO\UpdateActiveTableCommand;
use App\Application\Table\DTO\UpdateTableCommand;
use App\Domain\Restaurant\Entities\Table;
use Illuminate\Support\Collection;

interface TableRepositoryInterface
{
    public function exist(int $idEmpleado): bool;
    public function existTable(int $idEmpleado, string $mesa): bool;
    public function find(int $restaurant_id): array;
    public function create(AddTableCommand $command): Table;
    public function update(UpdateTableCommand $table): Table;
    public function updateActive(UpdateActiveTableCommand $table): Table;
    public function delete(int $id): bool;
    public function findById(int $id): Table;
}

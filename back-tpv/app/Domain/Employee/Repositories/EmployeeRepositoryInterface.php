<?php

namespace App\Domain\Employee\Repositories;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Domain\Employee\Entities\Employee;

interface EmployeeRepositoryInterface
{
    public function exist(int $idEmpleado): bool;
    public function find(int $restaurant_id): array;
    public function create(AddEmployeeCommand $command): Employee;
    public function softDelete(int $id): bool;
}

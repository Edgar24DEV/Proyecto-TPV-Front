<?php

namespace App\Application\Employee\UseCases;

use App\Application\Employee\DTO\DeleteEmployeeRestaurantCommand;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;

class DeleteEmployeeRestaurantUseCase
{
    public function __construct(
        private readonly EloquentEmployeeRepository $employeeRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
    ) {}

    public function __invoke(DeleteEmployeeRestaurantCommand $command)   
    {
        $this->validateOrFail(
            $command->getIdRestaurante(),
            $command->getIdEmpleado(),
        );

        return $this->employeeRepository->deleteEmployeeRestaurant($command);
    }
    private function validateOrFail(int $idRestaurante, int $idEmpleado): void
    {
        if ($idEmpleado <= 0 || !$this->employeeRepository->exist($idEmpleado)) {
            throw new \Exception("ID restaurante inválido");
        }
        if ($idRestaurante <= 0 || !$this->restaurantRepository->exist($idRestaurante)) {
            throw new \Exception("ID empresa inválido");
        }
    }
}

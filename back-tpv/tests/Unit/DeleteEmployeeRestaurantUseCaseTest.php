<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\DeleteEmployeeRestaurantCommand;
use App\Application\Employee\UseCases\DeleteEmployeeRestaurantUseCase;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use PHPUnit\Framework\TestCase;

class DeleteEmployeeRestaurantUseCaseTest extends TestCase
{
    public function test_delete_employee_from_restaurant_successfully()
    {
        // Arrange
        $command = new DeleteEmployeeRestaurantCommand(idEmpleado: 1, idRestaurante: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(true);
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $employeeRepo->method('deleteEmployeeRestaurant')->with($command)->willReturn(true);

        // Caso de uso
        $useCase = new DeleteEmployeeRestaurantUseCase($employeeRepo, $restaurantRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_employee_fails_when_invalid_employee_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID restaurante inválido");

        $command = new DeleteEmployeeRestaurantCommand(idEmpleado: -1, idRestaurante: 1); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteEmployeeRestaurantUseCase($employeeRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_delete_employee_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new DeleteEmployeeRestaurantCommand(idEmpleado: 1, idRestaurante: -1); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(true);
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteEmployeeRestaurantUseCase($employeeRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

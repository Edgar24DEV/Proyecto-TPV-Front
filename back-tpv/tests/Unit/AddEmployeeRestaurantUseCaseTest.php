<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\AddEmployeeRestaurantCommand;
use App\Application\Employee\UseCases\AddEmployeeRestaurantUseCase;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use PHPUnit\Framework\TestCase;

class AddEmployeeRestaurantUseCaseTest extends TestCase
{
    public function test_add_employee_to_restaurant_successfully()
    {
        // Arrange
        $command = new AddEmployeeRestaurantCommand(idEmpleado: 1, idRestaurante: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(true);
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $employeeRepo->method('addEmployeeRestaurant')->with($command)->willReturn(true);

        // Caso de uso
        $useCase = new AddEmployeeRestaurantUseCase($employeeRepo, $restaurantRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_add_employee_fails_when_invalid_employee_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empleado inválido");

        $command = new AddEmployeeRestaurantCommand(idEmpleado: -1, idRestaurante: 1); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(false);

        // Caso de uso
        $useCase = new AddEmployeeRestaurantUseCase($employeeRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_employee_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID restaurante inválido");

        $command = new AddEmployeeRestaurantCommand(idEmpleado: 1, idRestaurante: -1); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(true);
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new AddEmployeeRestaurantUseCase($employeeRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

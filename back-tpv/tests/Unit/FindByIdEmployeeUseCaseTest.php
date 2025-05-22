<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use PHPUnit\Framework\TestCase;

class FindByIdEmployeeUseCaseTest extends TestCase
{
    public function test_find_employee_successfully()
    {
        // Arrange
        $command = new FindByIdEmployeeCommand(id: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Mock existing employee
        $employee = new Employee(
            id: 1,
            nombre: "Juan Pérez",
            pin: "1234",
            idRol: 2,
            idEmpresa: 3
        );

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);
        $employeeRepo->method('findById')->with($command->getId())->willReturn($employee);
        $employeeService->method('showEmployeeInfoSimple')->with($employee)->willReturn($employee);

        // Caso de uso
        $useCase = new FindByIdEmployeeUseCase($employeeRepo, $restaurantRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Employee::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals("Juan Pérez", $result->nombre);
    }

    public function test_find_employee_fails_when_invalid_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empleado inválido");

        $command = new FindByIdEmployeeCommand(id: -1); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new FindByIdEmployeeUseCase($employeeRepo, $restaurantRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use PHPUnit\Framework\TestCase;

class DeleteEmployeeUseCaseTest extends TestCase
{
    public function test_delete_employee_successfully()
    {
        // Arrange
        $command = new DeleteEmployeeCommand(id: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Mock employee
        $employee = new Employee(
            id: 1,
            nombre: "Juan Pérez",
            pin: "1234",
            idRol: 2,
            idEmpresa: 3
        );

        // Expectations
        $employeeRepo->method('findById')->with($command->getId())->willReturn($employee);
        $employeeRepo->method('softDelete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteEmployeeUseCase($employeeRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_employee_fails_when_employee_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Empleado no encontrado.");

        $command = new DeleteEmployeeCommand(id: 999); // ID inexistente

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('findById')->willThrowException(new \Exception("Empleado no encontrado."));

        // Caso de uso
        $useCase = new DeleteEmployeeUseCase($employeeRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\LoginEmployeeCommand;
use App\Application\Employee\UseCases\LoginEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class LoginEmployeeUseCaseTest extends TestCase
{
    public function test_login_employee_successfully()
    {
        // Arrange
        $command = new LoginEmployeeCommand(id: 1, pin: 1234);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Mock logged-in employee
        $loggedEmployee = new Employee(
            id: 1,
            nombre: "Juan Pérez",
            pin: "1234",
            idRol: 2,
            idEmpresa: 3
        );

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);
        $employeeRepo->method('login')->with($command)->willReturn($loggedEmployee);
        $employeeService->method('showEmployeeInfoSimple')->with($loggedEmployee)->willReturn($loggedEmployee);

        // Caso de uso
        $useCase = new LoginEmployeeUseCase($employeeRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Employee::class, $result);
        $this->assertEquals("Juan Pérez", $result->nombre);
        $this->assertEquals("1234", $result->pin);
        $this->assertEquals(2, $result->idRol);
        $this->assertEquals(3, $result->idEmpresa);
    }

    public function test_login_employee_fails_when_invalid_employee_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empleado inválido");

        $command = new LoginEmployeeCommand(id: -1, pin: 1234); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new LoginEmployeeUseCase($employeeRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_login_employee_handles_exception()
    {
        // Arrange
        $command = new LoginEmployeeCommand(id: 1, pin: 1234);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);
        $employeeRepo->method('login')->with($command)->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new LoginEmployeeUseCase($employeeRepo, $employeeService);

        // Act & Assert
        $useCase($command);

    }
}

<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\UseCases\UpdateEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;

class UpdateEmployeeUseCaseTest extends TestCase
{
    public function test_update_employee_successfully()
    {
        // Arrange
        $command = new UpdateEmployeeCommand(id: 1, nombre: "Juan Pérez", pin: "5678", idEmpresa: 3, idRol: 2);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Mock updated employee
        $updatedEmployee = new Employee(
            id: 1,
            nombre: "Juan Pérez",
            pin: "5678",
            idRol: 2,
            idEmpresa: 3
        );

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $roleRepo->method('exist')->with($command->getIdRol())->willReturn(true);
        $employeeRepo->method('update')->with($command)->willReturn($updatedEmployee);
        $employeeService->method('showEmployeeInfoSimple')->with($updatedEmployee)->willReturn($updatedEmployee);

        // Caso de uso
        $useCase = new UpdateEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Employee::class, $result);
        $this->assertEquals("Juan Pérez", $result->nombre);
        $this->assertEquals("5678", $result->pin);
        $this->assertEquals(2, $result->idRol);
        $this->assertEquals(3, $result->idEmpresa);
    }

    public function test_update_employee_fails_when_invalid_employee_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empleado inválido");

        $command = new UpdateEmployeeCommand(id: -1, nombre: "Juan Pérez", pin: "5678", idEmpresa: 3, idRol: 2);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_employee_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new UpdateEmployeeCommand(id: 1, nombre: "Juan Pérez", pin: "5678", idEmpresa: -1, idRol: 2);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_employee_fails_when_invalid_role_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID rol inválido");

        $command = new UpdateEmployeeCommand(id: 1, nombre: "Juan Pérez", pin: "5678", idEmpresa: 3, idRol: -1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);

        $roleRepo->method('exist')->with($command->getIdRol())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

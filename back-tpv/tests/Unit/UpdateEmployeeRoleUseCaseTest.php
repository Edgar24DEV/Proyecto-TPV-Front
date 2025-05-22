<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\UpdateEmployeeRoleCommand;
use App\Application\Employee\UseCases\UpdateEmployeeRoleUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class UpdateEmployeeRoleUseCaseTest extends TestCase
{
    public function test_update_employee_role_successfully()
    {
        // Arrange
        $command = new UpdateEmployeeRoleCommand(id: 1, idRol: 2);

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
            pin: "1234",
            idRol: 2,
            idEmpresa: 3
        );

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);
        $roleRepo->method('exist')->with($command->getIdRol())->willReturn(true);
        $employeeRepo->method('updateRole')->with($command)->willReturn($updatedEmployee);
        $employeeService->method('showEmployeeInfoSimple')->with($updatedEmployee)->willReturn($updatedEmployee);

        // Caso de uso
        $useCase = new UpdateEmployeeRoleUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Employee::class, $result);
        $this->assertEquals(2, $result->idRol);
    }

    public function test_update_employee_role_fails_when_invalid_employee_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empleado inválido");

        $command = new UpdateEmployeeRoleCommand(id: -1, idRol: 2); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateEmployeeRoleUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_employee_role_fails_when_invalid_role_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID rol inválido");

        $command = new UpdateEmployeeRoleCommand(id: 5, idRol: -1); // ID rol inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getId())->willReturn(true);
        $roleRepo->method('exist')->with($command->getIdRol())->willReturn(false);


        // Caso de uso
        $useCase = new UpdateEmployeeRoleUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

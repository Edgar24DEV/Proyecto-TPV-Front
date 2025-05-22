<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\UseCases\AddEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class AddEmployeeUseCaseTest extends TestCase
{
    public function test_add_employee_successfully()
    {
        // Arrange
        $command = new AddEmployeeCommand(nombre: "Juan Pérez", pin: "1234", idEmpresa: 3, idRol: 2, idRestaurante: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Mock created employee
        $newEmployee = new Employee(
            id: 1,
            nombre: "Juan Pérez",
            pin: "1234",
            idRol: 2,
            idEmpresa: 3
        );

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('existwithCompany')->with($command->getIdRestaurante(), $command->getIdEmpresa())->willReturn(true);
        $roleRepo->method('exist')->with($command->getIdRol())->willReturn(true);
        $employeeRepo->method('create')->with($command)->willReturn($newEmployee);
        $employeeService->method('showEmployeeInfoSimple')->with($newEmployee)->willReturn($newEmployee);

        // Caso de uso
        $useCase = new AddEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Employee::class, $result);
        $this->assertEquals("Juan Pérez", $result->nombre);
        $this->assertEquals("1234", $result->pin);
        $this->assertEquals(2, $result->idRol);
        $this->assertEquals(3, $result->idEmpresa);
    }

    public function test_add_employee_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID restaurante inválido");

        $command = new AddEmployeeCommand(nombre: "Juan Pérez", pin: "1234", idEmpresa: 3, idRol: 2, idRestaurante: -1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new AddEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_employee_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new AddEmployeeCommand(nombre: "Juan Pérez", pin: "1234", idEmpresa: -1, idRol: 2, idRestaurante: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new AddEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_employee_fails_when_restaurant_not_belongs_to_company()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El restaurante no es de esa empresa");

        $command = new AddEmployeeCommand(nombre: "Juan Pérez", pin: "1234", idEmpresa: 3, idRol: 2, idRestaurante: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('existwithCompany')->with($command->getIdRestaurante(), $command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new AddEmployeeUseCase($employeeRepo, $restaurantRepo, $roleRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\ListEmployeesCompanyCommand;
use App\Application\Employee\UseCases\ListEmployeesCompanyUseCase;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListEmployeesCompanyUseCaseTest extends TestCase
{
    public function test_list_employees_successfully()
    {
        // Arrange
        $command = new ListEmployeesCompanyCommand(idEmpresa: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Mock employee list
        $employees = [
            ['id' => 1, 'nombre' => 'Juan Pérez'],
            ['id' => 2, 'nombre' => 'Ana García'],
        ];

        $processedEmployees = new Collection([
            ['id' => 1, 'nombre' => 'Juan Pérez - procesado'],
            ['id' => 2, 'nombre' => 'Ana García - procesado'],
        ]);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $employeeRepo->method('findByCompany')->with($command->getIdEmpresa())->willReturn($employees);
        $employeeService->method('showEmployeeInfo')->with($employees)->willReturn($processedEmployees);

        // Caso de uso
        $useCase = new ListEmployeesCompanyUseCase($employeeRepo, $companyRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Juan Pérez - procesado", $result[0]['nombre']);
        $this->assertEquals("Ana García - procesado", $result[1]['nombre']);
    }

    public function test_list_employees_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListEmployeesCompanyCommand(idEmpresa: 999); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new ListEmployeesCompanyUseCase($employeeRepo, $companyRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

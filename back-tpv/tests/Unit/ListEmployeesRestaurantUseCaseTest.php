<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\ListEmployeesRestaurantCommand;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListEmployeesRestaurantUseCaseTest extends TestCase
{
    public function test_list_employees_by_restaurant_successfully()
    {
        // Arrange
        $command = new ListEmployeesRestaurantCommand(idRestaurante: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
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
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(true);
        $employeeRepo->method('find')->with($command->getIdRestaurant())->willReturn($employees);
        $employeeService->method('showEmployeeInfo')->with($employees)->willReturn($processedEmployees);

        // Caso de uso
        $useCase = new ListEmployeesRestaurantUseCase($employeeRepo, $restaurantRepo, $employeeService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Juan Pérez - procesado", $result[0]['nombre']);
        $this->assertEquals("Ana García - procesado", $result[1]['nombre']);
    }

    public function test_list_employees_by_restaurant_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListEmployeesRestaurantCommand(idRestaurante: 999); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $employeeService = $this->createMock(EmployeeService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(false);

        // Caso de uso
        $useCase = new ListEmployeesRestaurantUseCase($employeeRepo, $restaurantRepo, $employeeService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

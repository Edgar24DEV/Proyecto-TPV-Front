<?php

namespace Tests\Unit\Application\Employee\UseCases;

use App\Application\Employee\DTO\ListEmployeeRestaurantsCommand;
use App\Application\Employee\UseCases\ListEmployeeRestaurantsUseCase;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListEmployeeRestaurantsUseCaseTest extends TestCase
{
    public function test_list_employee_restaurants_successfully()
    {
        // Arrange
        $command = new ListEmployeeRestaurantsCommand(idEmpresa: 1, idEmpleado: 1);

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Mock restaurant list
        $restaurants = [
            ['id' => 1, 'nombre' => 'Restaurante A'],
            ['id' => 2, 'nombre' => 'Restaurante B'],
        ];

        $processedRestaurants = new Collection([
            ['id' => 1, 'nombre' => 'Restaurante A - procesado'],
            ['id' => 2, 'nombre' => 'Restaurante B - procesado'],
        ]);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(true);
        $employeeRepo->method('findEmployeeRestaurantsByCompany')->with($command->getIdEmpresa(), $command->getIdEmpleado())->willReturn($restaurants);
        $restaurantService->method('showRestaurantInfo')->with($restaurants)->willReturn($processedRestaurants);

        // Caso de uso
        $useCase = new ListEmployeeRestaurantsUseCase($employeeRepo, $companyRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Restaurante A - procesado", $result[0]['nombre']);
        $this->assertEquals("Restaurante B - procesado", $result[1]['nombre']);
    }

    public function test_list_employee_restaurants_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID de la compañia no existe");

        $command = new ListEmployeeRestaurantsCommand(idEmpresa: 999, idEmpleado: 1); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(true);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);



        // Caso de uso
        $useCase = new ListEmployeeRestaurantsUseCase($employeeRepo, $companyRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_list_employee_restaurants_fails_when_invalid_employee_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID del empleado no existe");

        $command = new ListEmployeeRestaurantsCommand(idEmpresa: 1, idEmpleado: 999); // ID inválido

        // Mocks
        $employeeRepo = $this->createMock(EloquentEmployeeRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Expectations
        $employeeRepo->method('exist')->with($command->getIdEmpleado())->willReturn(false);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);


        // Caso de uso
        $useCase = new ListEmployeeRestaurantsUseCase($employeeRepo, $companyRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

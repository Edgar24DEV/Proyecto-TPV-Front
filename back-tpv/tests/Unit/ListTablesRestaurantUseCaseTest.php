<?php

namespace Tests\Unit;

use App\Application\Table\UseCases\ListTablesRestaurantUseCase;
use App\Application\Table\DTO\ListTablesRestaurantCommand;
use App\Domain\Restaurant\Services\TableService;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListTablesRestaurantUseCaseTest extends TestCase
{
    public function test_list_tables_successfully()
    {
        // Arrange
        $restaurantId = 1;
        $command = new ListTablesRestaurantCommand($restaurantId);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $tableService = $this->createMock(TableService::class);

        // Mock data
        $tables = [
            ['id' => 1, 'mesa' => 'Mesa 1', 'activo' => true],
            ['id' => 2, 'mesa' => 'Mesa 2', 'activo' => true]
        ];

        $processedTables = new Collection([
            (object) ['id' => 1, 'mesa' => 'Mesa 1 Info', 'activo' => true],
            (object) ['id' => 2, 'mesa' => 'Mesa 2 Info', 'activo' => true]
        ]);

        // Expectations
        $restaurantRepo->method('exist')
            ->with($restaurantId)
            ->willReturn(true);

        $tableRepo->method('find')
            ->with($restaurantId)
            ->willReturn($tables); // Mantiene la respuesta como array

        $tableService->method('showTableInfo')
            ->with($tables)
            ->willReturn($processedTables);

        // Caso de uso
        $useCase = new ListTablesRestaurantUseCase(
            $tableRepo,
            $restaurantRepo,
            $tableService
        );

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals('Mesa 1 Info', $result[0]->mesa);
        $this->assertEquals('Mesa 2 Info', $result[1]->mesa);
    }


    public function test_list_tables_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID inválido");

        $invalidRestaurantId = 0;
        $command = new ListTablesRestaurantCommand($invalidRestaurantId);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $tableService = $this->createMock(TableService::class);

        // No need to set expectations as validation fails first

        // Caso de uso
        $useCase = new ListTablesRestaurantUseCase(
            $tableRepo,
            $restaurantRepo,
            $tableService
        );

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_list_tables_fails_when_restaurant_does_not_exist()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $nonExistentRestaurantId = 999;
        $command = new ListTablesRestaurantCommand($nonExistentRestaurantId);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $tableService = $this->createMock(TableService::class);

        // Expectations
        $restaurantRepo->method('exist')
            ->with($nonExistentRestaurantId)
            ->willReturn(false);

        // Caso de uso
        $useCase = new ListTablesRestaurantUseCase(
            $tableRepo,
            $restaurantRepo,
            $tableService
        );

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
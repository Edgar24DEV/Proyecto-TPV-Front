<?php

namespace Tests\Unit;

use App\Application\Table\UseCases\FindByIdTableUseCase;
use App\Application\Table\DTO\FindByIdTableCommand;
use App\Domain\Restaurant\Entities\Table;
use App\Domain\Restaurant\Services\TableService;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Repositories\EloquentTableRepository;

class FindByIdTableUseCaseTest extends TestCase
{
    public function test_find_table_by_id_successfully()
    {
        // Arrange
        $tableId = 1;
        $command = new FindByIdTableCommand($tableId);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $tableService = $this->createMock(TableService::class);

        $table = new Table(
            id: 1,
            mesa: 'A1',
            activo: true,
            idUbicacion: 1,
            posX: 5,
            posY: 10
        );

        $tableInfo = new Table(
            id: 1,
            mesa: 'A1 Info',
            activo: true,
            idUbicacion: 1,
            posX: 5,
            posY: 10
        );

        // Expectations
        $tableRepo->method('exist')
            ->with($tableId)
            ->willReturn(true);

        $tableRepo->method('findById')
            ->with($tableId)
            ->willReturn($table);

        $tableService->method('showTableInfoSimple')
            ->with($table)
            ->willReturn($tableInfo);

        // Caso de uso
        $useCase = new FindByIdTableUseCase($tableRepo, $tableService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Table::class, $result);
        $this->assertEquals('A1 Info', $result->mesa);
        $this->assertEquals(5, $result->posX);
        $this->assertEquals(10, $result->posY);
    }

    public function test_find_table_fails_when_table_does_not_exist()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID mesa inválido");

        $tableId = 999; // ID inválido
        $command = new FindByIdTableCommand($tableId);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $tableService = $this->createMock(TableService::class);

        // Expectations
        $tableRepo->method('exist')
            ->with($tableId)
            ->willReturn(false);

        // Caso de uso
        $useCase = new FindByIdTableUseCase($tableRepo, $tableService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_find_table_fails_when_id_is_invalid()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID mesa inválido");

        $tableId = -1; // ID inválido
        $command = new FindByIdTableCommand($tableId);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $tableService = $this->createMock(TableService::class);

        // No es necesario mockear exist() porque la validación es primero

        // Caso de uso
        $useCase = new FindByIdTableUseCase($tableRepo, $tableService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
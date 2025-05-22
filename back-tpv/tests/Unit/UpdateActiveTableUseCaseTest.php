<?php

namespace Tests\Unit;

use App\Application\Table\UseCases\UpdateActiveTableUseCase;
use App\Application\Table\DTO\UpdateActiveTableCommand;
use App\Domain\Restaurant\Entities\Table;
use App\Domain\Restaurant\Services\TableService;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class UpdateActiveTableUseCaseTest extends TestCase
{
    public function test_update_active_table_successfully()
    {
        // Arrange
        $command = new UpdateActiveTableCommand(
            id: 1,
            mesa: "Mesa 1",
            activo: true,
            idUbicacion: 2,
            posX: 10,
            posY: 20
        );

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $tableService = $this->createMock(TableService::class);

        // Mock table data
        $updatedTable = new Table(
            id: 1,
            mesa: "Mesa 1",
            activo: true,
            idUbicacion: 2,
            posX: 10,
            posY: 20
        );

        $tableInfo = new Table(
            id: 1,
            mesa: "Mesa 1 Info",
            activo: true,
            idUbicacion: 2,
            posX: 10,
            posY: 20
        );

        // Expectations
        $tableRepo->method('exist')->with($command->getId())->willReturn(true);
        $locationRepo->method('exist')->with($command->getIdUbicacion())->willReturn(true);
        $tableRepo->method('updateActive')->with($command)->willReturn($updatedTable);
        $tableService->method('showTableInfoSimple')->with($updatedTable)->willReturn($tableInfo);

        // Caso de uso
        $useCase = new UpdateActiveTableUseCase($tableRepo, $locationRepo, $tableService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Table::class, $result);
        $this->assertEquals("Mesa 1 Info", $result->mesa);
    }

    public function test_update_active_table_fails_when_invalid_table_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID mesa inválido");

        $command = new UpdateActiveTableCommand(
            id: 0, // ID inválido
            mesa: "Mesa 1",
            activo: true,
            idUbicacion: 2,
            posX: 10,
            posY: 20
        );

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $tableService = $this->createMock(TableService::class);

        $tableRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateActiveTableUseCase($tableRepo, $locationRepo, $tableService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_active_table_fails_when_invalid_location_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID ubicación inválido");

        $command = new UpdateActiveTableCommand(
            id: 1,
            mesa: "Mesa 1",
            activo: true,
            idUbicacion: 0, // ID ubicación inválido
            posX: 10,
            posY: 20
        );

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $tableService = $this->createMock(TableService::class);

        $tableRepo->method('exist')->with($command->getId())->willReturn(true);
        $locationRepo->method('exist')->with($command->getIdUbicacion())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateActiveTableUseCase($tableRepo, $locationRepo, $tableService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}

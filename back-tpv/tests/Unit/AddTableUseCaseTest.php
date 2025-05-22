<?php

namespace Tests\Unit;

use App\Application\Table\UseCases\AddTableUseCase;
use App\Application\Table\DTO\AddTableCommand;
use App\Domain\Restaurant\Entities\Table;
use App\Domain\Restaurant\Services\TableService;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;

class AddTableUseCaseTest extends TestCase
{
    public function test_add_table_successfully()
    {
        // Arrange
        $command = new AddTableCommand(
            idUbicacion: 1,
            mesa: 'A1',
            posX: 5,
            posY: 10,
        );

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $tableService = $this->createMock(TableService::class);

        $table = new Table(
            id: 1,
            mesa: 'A1',
            activo: true,
            idUbicacion: 1,
            posX: 5,
            posY: 10
        );

        // Expectations
        $locationRepo->method('exist')
            ->with(1)
            ->willReturn(true);

        $tableRepo->method('existTable')
            ->with(1, 'A1')
            ->willReturn(false);

        $tableRepo->method('create')
            ->with($command)
            ->willReturn($table);

        $tableService->method('showTableInfoSimple')
            ->with($table)
            ->willReturn($table);

        // Caso de uso
        $useCase = new AddTableUseCase(
            $tableRepo,
            $restaurantRepo,
            $roleRepo,
            $locationRepo,
            $tableService
        );

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Table::class, $result);
        $this->assertEquals('A1', $result->mesa);
        $this->assertEquals(5, $result->posX);
        $this->assertEquals(10, $result->posY);
    }

    public function test_add_table_fails_when_location_does_not_exist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID ubicación inválido");

        $command = new AddTableCommand(
            idUbicacion: 0,
            mesa: 'A1',
            posX: 5,
            posY: 10,
        );

        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $tableService = $this->createMock(TableService::class);

        $locationRepo->method('exist')->willReturn(false);

        $useCase = new AddTableUseCase(
            $tableRepo,
            $restaurantRepo,
            $roleRepo,
            $locationRepo,
            $tableService
        );

        $useCase($command);
    }

    public function test_add_table_fails_when_table_already_exists()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Ya existe la mesa");

        $command = new AddTableCommand(
            idUbicacion: 1,
            mesa: 'A1',
            posX: 5,
            posY: 10,
        );

        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $tableService = $this->createMock(TableService::class);

        $locationRepo->method('exist')->with(1)->willReturn(true);
        $tableRepo->method('existTable')->with(1, 'A1')->willReturn(true);

        $useCase = new AddTableUseCase(
            $tableRepo,
            $restaurantRepo,
            $roleRepo,
            $locationRepo,
            $tableService
        );

        $useCase($command);
    }


}

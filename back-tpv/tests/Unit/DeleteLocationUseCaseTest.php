<?php

namespace Tests\Unit\Application\Location\UseCases;

use App\Application\Location\DTO\DeleteLocationCommand;
use App\Application\Location\UseCases\DeleteLocationUseCase;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class DeleteLocationUseCaseTest extends TestCase
{
    public function test_delete_location_successfully()
    {
        // Arrange
        $command = new DeleteLocationCommand(id: 1);

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(true);
        $locationRepo->method('delete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteLocationUseCase($locationRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_location_fails_when_location_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID ubicacion inválido");

        $command = new DeleteLocationCommand(id: 999); // Ubicación inexistente

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteLocationUseCase($locationRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_delete_location_handles_exception()
    {
        // Arrange
        $command = new DeleteLocationCommand(id: 1);

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteLocationUseCase($locationRepo);

        // Act & Assert

        $useCase($command);

    }
}

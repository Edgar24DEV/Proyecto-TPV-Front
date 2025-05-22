<?php

namespace Tests\Unit\Application\Table\UseCases;

use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Table\DTO\DeleteTableCommand;
use App\Application\Table\UseCases\DeleteTableUseCase;
use App\Domain\Order\Entities\Order;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class DeleteTableUseCaseTest extends TestCase
{
    public function test_delete_table_successfully()
    {
        // Arrange
        $command = new DeleteTableCommand(1);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $logMock = $this->createMock(\Illuminate\Log\Logger::class);
        Log::shouldReceive('channel')->with('restaurant')->andReturn($logMock);

        // Expectations
        $tableRepo->method('exist')
            ->with(1)
            ->willReturn(true);

        $orderRepo->method('getOrderTableDelete')
            ->with(new GetOrderCommand(1))
            ->willReturn(new Order(id: -1, estado: '', fechaInicio: null, fechaFin: null, comensales: 0.00, idMesa: -1));

        $tableRepo->method('delete')
            ->with(1)
            ->willReturn(true);

        // Caso de uso
        $useCase = new DeleteTableUseCase(
            $tableRepo,
            $orderRepo
        );

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
        $logMock->expects($this->never())->method('error');
    }

    public function test_delete_table_fails_when_table_does_not_exist()
    {
        // Arrange
        $command = new DeleteTableCommand(0);

        // Mocks
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $logMock = $this->createMock(\Illuminate\Log\Logger::class);
        Log::shouldReceive('channel')->with('restaurant')->andReturn($logMock);

        // Expectations
        $tableRepo->method('exist')
            ->with(arguments: 0)
            ->willReturn(false);

        // Caso de uso
        $useCase = new DeleteTableUseCase(
            $tableRepo,
            $orderRepo
        );

        // Act
        try {
            $useCase($command);
            $this->fail('Se esperaba una excepción');
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals("ID mesa inválido", $e->getMessage());
            $logMock->expects($this->never())->method('error');
        }
    }


}
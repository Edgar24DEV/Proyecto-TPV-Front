<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\GetOrderCommand;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class GetOrderUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
        private readonly EloquentTableRepository $tableRepository,
        private readonly OrderService $orderService,
    ) {}
    public function __invoke(GetOrderCommand $command): Order
    {
        $this->validateOrFail($command);

        try {
            $order = $this->orderRepository->getOrder($command);
            return $this->orderService->showOrderInfoSimple($order);
        } catch (\Exception $e) {
            // Si no hay pedido, creamos uno nuevo usando AddOrderCommand

            $orderVacio = new Order(
                id: -1,
                estado: null,
                fechaInicio: null,
                fechaFin: null,
                comensales: null,
                idMesa: -1,
            );
            return $orderVacio;
        }
    }

    private function validateOrFail(GetOrderCommand $command): void
    {
        if ($command->getIdMesa() <= 0 || !$this->tableRepository->exist($command->getIdMesa())) {
            throw new \Exception("ID mesa inválido");
        }
    }
}

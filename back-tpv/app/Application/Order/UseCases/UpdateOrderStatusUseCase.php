<?php

namespace App\Application\Order\UseCases;


use App\Application\Order\DTO\UpdateOrderStatusCommand;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatusUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
        private readonly OrderService $orderService,
    ) {
    }
    public function __invoke(UpdateOrderStatusCommand $command): Order
    {
        $this->validateOrFail(
            $command,
        );

        // $employee = $this->employeeService->requestEmployee($command);
        try {
            $order = $this->orderRepository->updateStatus($command);
            $orderInfo = $this->orderService->showOrderInfoSimple($order);
        } catch (\Exception $e) {
            $orderVacio = new Order(
                id: -1,
                estado: $e,
                fechaInicio: null,
                fechaFin: null,
                comensales: null,
                idMesa: -1,
            );
            return $orderVacio;
        }
        return $orderInfo;
    }


    private function validateOrFail(UpdateOrderStatusCommand $idOrder): void
    {
        $order = $this->orderRepository->findByID($idOrder);
        if ($idOrder->getId() <= 0 || !$this->orderRepository->exist($idOrder->getId())) {
            throw new \Exception("ID de pedido inválido");
        }
        if ($order->getEstado() == "Cancelado" || $order->getEstado() == "Cerrado") {
            throw new \Exception("El pedido no puede ser modificado porque ya está cancelado o cerrado");
        }

    }
}
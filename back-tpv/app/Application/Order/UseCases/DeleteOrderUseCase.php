<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class DeleteOrderUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
    ) {
    }
    public function __invoke(DeleteOrderCommand $command): bool
    {
        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->orderRepository->delete($command->getId());
        } catch (\Exception $e) {
            return $respuesta;
        }
        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->orderRepository->exist($id)) {
            throw new \Exception("ID de pedido inválido");
        }
    }
}
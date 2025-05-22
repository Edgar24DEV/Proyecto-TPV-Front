<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTO\AddOrderCommand;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class AddOrderUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
        private readonly EloquentTableRepository $tableRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly OrderService $orderService,
    ) {
    }
    public function __invoke(AddOrderCommand $command): Order
    {

        $this->validateOrFail($command);

        try {
            $order = $this->orderRepository->create($command);
            $orderInfo = $this->orderService->showOrderInfoSimple($order);
        } catch (\Exception $e) {
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
        return $orderInfo;
    }


    private function validateOrFail(AddOrderCommand $command): void
    {
        if ($command->getIdMesa() <= 0 || !$this->tableRepository->exist($command->getIdMesa())) {
            throw new \Exception("ID mesa inválido");
        }

        if (!is_null($command->getComensales()) && $command->getComensales() <= 0) {
            throw new \InvalidArgumentException("El número de comensales debe ser mayor a 0");
        }

    }

}
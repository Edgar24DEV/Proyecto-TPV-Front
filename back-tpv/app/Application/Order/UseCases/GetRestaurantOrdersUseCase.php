<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\GetOngoingOrdersCommand;
use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Order\DTO\GetRestaurantOrdersCommand;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class GetRestaurantOrdersUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly OrderService $orderService,
    ) {
    }
    public function __invoke(GetRestaurantOrdersCommand $command): Collection
    {
        $this->validateOrFail($command);

        $orders = $this->orderRepository->getRestaurantOrders($command);
        $orders = $this->orderService->showOrderInfo($orders);

        return $orders;

    }

    private function validateOrFail(GetRestaurantOrdersCommand $command): void
    {
        if ($command->getIdRestaurante() <= 0 || !$this->restaurantRepository->exist($command->getIdRestaurante())) {
            throw new \Exception("ID restaurante inválido");
        }
    }
}

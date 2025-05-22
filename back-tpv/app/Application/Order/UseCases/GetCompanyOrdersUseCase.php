<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\GetCompanyOrdersCommand;
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


class GetCompanyOrdersUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly OrderService $orderService,
    ) {
    }
    public function __invoke(GetCompanyOrdersCommand $command): Collection
    {
        $this->validateOrFail($command);

        $orders = $this->orderRepository->getCompanyOrders($command);
        $orders = $this->orderService->showOrderInfo($orders);

        return $orders;

    }

    private function validateOrFail(GetCompanyOrdersCommand $command): void
    {
        if ($command->getIdEmpresa() <= 0 || !$this->companyRepository->exist($command->getIdEmpresa())) {
            throw new \Exception("ID restaurante inválido");
        }
    }
}

<?php

namespace App\Application\Order\UseCases;

use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class UpdateOrderDinersUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
        private readonly OrderService $orderService,
    ) {
    }
    public function __invoke(UpdateOrderDinersCommand $command): Order
    {
        $this->validateOrFail(
            $command->getId(),
        );

        // $employee = $this->employeeService->requestEmployee($command);
        try {
            $employee = $this->orderRepository->updateDiners($command);
            $orderInfo = $this->orderService->showOrderInfoSimple($employee);
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


    private function validateOrFail(int $idOrder): void
    {
        if ($idOrder <= 0 || !$this->orderRepository->exist($idOrder)) {
            throw new \Exception("ID de pedido inválido");
        }
    }
}
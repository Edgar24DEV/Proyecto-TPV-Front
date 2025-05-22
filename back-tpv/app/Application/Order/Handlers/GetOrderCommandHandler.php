<?php
// UpdateOrderHandler.php
namespace App\Application\Order\Handlers;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Order\DTO\UpdateOrderCommand;
use App\Application\Order\DTO\UpdateOrderDTO;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Application\Order\UseCases\GetOrderUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Audit\Services\AuditService;

class GetOrderCommandHandler
{
    private GetOrderUseCase $getOrderUseCase;


    public function __construct(GetOrderUseCase $getOrderUseCase)
    {
        $this->getOrderUseCase = $getOrderUseCase;
    }

    public function handle(GetOrderCommand $command): Order
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->getOrderUseCase->__invoke($command);
    }

    private function isOrderAllowedToUpdate(int $orderId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}
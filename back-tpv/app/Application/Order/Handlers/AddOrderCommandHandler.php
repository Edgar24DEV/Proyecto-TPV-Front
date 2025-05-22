<?php
// UpdateOrderHandler.php
namespace App\Application\Order\Handlers;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\UpdateOrderCommand;
use App\Application\Order\DTO\UpdateOrderDTO;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Audit\Services\AuditService;

class AddOrderCommandHandler
{
    private AddOrderUseCase $addOrderUseCase;


    public function __construct(AddOrderUseCase $addOrderUseCase)
    {
        $this->addOrderUseCase = $addOrderUseCase;
    }

    public function handle(AddOrderCommand $command): Order
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->addOrderUseCase->__invoke($command);
    }

    private function isOrderAllowedToUpdate(int $orderId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}
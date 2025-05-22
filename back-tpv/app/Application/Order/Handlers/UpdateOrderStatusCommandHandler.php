<?php
// UpdateEmployeeHandler.php
namespace App\Application\Order\Handlers;

use App\Application\Order\DTO\UpdateOrderStatusCommand;
use App\Application\Order\UseCases\UpdateOrderStatusUseCase;
use App\Domain\Order\Entities\Order;

class UpdateOrderStatusCommandHandler
{
    private UpdateOrderStatusUseCase $updateOrderStatusUseCase;


    public function __construct(UpdateOrderStatusUseCase $updateOrderStatusUseCase)
    {
        $this->updateOrderStatusUseCase = $updateOrderStatusUseCase;
    }

    public function handle(UpdateOrderStatusCommand $command): Order
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateOrderStatusUseCase->__invoke($command);
    }

}
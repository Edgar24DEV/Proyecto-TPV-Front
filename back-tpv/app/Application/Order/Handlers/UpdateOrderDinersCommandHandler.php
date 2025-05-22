<?php
// UpdateEmployeeHandler.php
namespace App\Application\Order\Handlers;

use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Application\Employee\UseCases\UpdateEmployeeUseCase;
use App\Application\Order\UseCases\UpdateOrderDinersUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;
use App\Domain\Order\Entities\Order;

class UpdateOrderDinersCommandHandler
{
    private UpdateOrderDinersUseCase $updateOrderDinersUseCase;


    public function __construct(UpdateOrderDinersUseCase $updateOrderDinersUseCase)
    {
        $this->updateOrderDinersUseCase = $updateOrderDinersUseCase;
    }

    public function handle(UpdateOrderDinersCommand $command): Order
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateOrderDinersUseCase->__invoke($command);
    }

}
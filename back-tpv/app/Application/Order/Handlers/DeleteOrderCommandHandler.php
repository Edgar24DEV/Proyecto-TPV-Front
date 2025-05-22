<?php
// UpdateRoleHandler.php
namespace App\Application\Order\Handlers;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Order\UseCases\DeleteOrderUseCase;
use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Application\Payment\UseCases\DeletePaymentUseCase;
use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\UseCases\DeleteRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Audit\Services\AuditService;

class DeleteOrderCommandHandler
{
    private DeleteOrderUseCase $deleteOrderUseCase;


    public function __construct(DeleteOrderUseCase $deleteOrderUseCase)
    {
        $this->deleteOrderUseCase = $deleteOrderUseCase;
    }

    public function handle(DeleteOrderCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteOrderUseCase->__invoke($command);
    }

}
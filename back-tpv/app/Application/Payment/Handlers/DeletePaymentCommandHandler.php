<?php
// UpdateRoleHandler.php
namespace App\Application\Payment\Handlers;

use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Application\Payment\UseCases\DeletePaymentUseCase;
use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\UseCases\DeleteRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Audit\Services\AuditService;

class DeletePaymentCommandHandler
{
    private DeletePaymentUseCase $deletePaymentUseCase;


    public function __construct(DeletePaymentUseCase $deletePaymentUseCase)
    {
        $this->deletePaymentUseCase = $deletePaymentUseCase;
    }

    public function handle(DeletePaymentCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deletePaymentUseCase->__invoke($command);
    }

}
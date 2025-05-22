<?php
// UpdateRoleHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Order\UseCases\DeleteOrderUseCase;
use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Application\Payment\UseCases\DeletePaymentUseCase;
use App\Application\Product\DTO\DeleteProductCommand;
use App\Application\Product\UseCases\DeleteProductUseCase;
use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\UseCases\DeleteRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Audit\Services\AuditService;

class DeleteProductCommandHandler
{
    private DeleteProductUseCase $deleteProductUseCase;


    public function __construct(DeleteProductUseCase $deleteProductUseCase)
    {
        $this->deleteProductUseCase = $deleteProductUseCase;
    }

    public function handle(DeleteProductCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteProductUseCase->__invoke($command);
    }

}
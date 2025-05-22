<?php
// UpdateRoleHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Order\UseCases\DeleteOrderUseCase;
use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Application\Payment\UseCases\DeletePaymentUseCase;
use App\Application\Product\DTO\DeleteProductCommand;
use App\Application\Product\DTO\GetProductCommand;
use App\Application\Product\UseCases\DeleteProductUseCase;
use App\Application\Product\UseCases\GetProductByIdUseCase;
use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\UseCases\DeleteRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Audit\Services\AuditService;
use App\Domain\Product\Entities\Product;

class GetProductCommandHandler
{
    private GetProductByIdUseCase $getProductByIdUseCase;


    public function __construct(GetProductByIdUseCase $getProductByIdUseCase)
    {
        $this->getProductByIdUseCase = $getProductByIdUseCase;
    }

    public function handle(GetProductCommand $command): Product
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->getProductByIdUseCase->__invoke($command);
    }

}
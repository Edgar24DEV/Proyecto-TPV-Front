<?php
// UpdateEmployeeHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Application\Employee\UseCases\UpdateEmployeeUseCase;
use App\Application\Order\UseCases\UpdateOrderDinersUseCase;
use App\Application\Product\DTO\UpdateProductCommand;
use App\Application\Product\UseCases\UpdateProductUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;
use App\Domain\Order\Entities\Order;
use App\Domain\Product\Entities\Product;

class UpdateProductCommandHandler
{
    private UpdateProductUseCase $updateProductUseCase;


    public function __construct(UpdateProductUseCase $updateProductUseCase)
    {
        $this->updateProductUseCase = $updateProductUseCase;
    }

    public function handle(UpdateProductCommand $command): Product
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateProductUseCase->__invoke($command);
    }

}
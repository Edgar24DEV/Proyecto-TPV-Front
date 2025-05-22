<?php
// UpdateEmployeeHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Product\DTO\UpdateDeactivateProductCommand;
use App\Application\Product\DTO\UpdateProductCommand;
use App\Application\Product\UseCases\UpdateDeactivateProductUseCase;
use App\Domain\Product\Entities\Product;

class UpdateDeactivateProductCommandHandler
{
    private UpdateDeactivateProductUseCase $updateDeactivateProductUseCase;


    public function __construct(UpdateDeactivateProductUseCase $updateDeactivateProductUseCase)
    {
        $this->updateDeactivateProductUseCase = $updateDeactivateProductUseCase;
    }

    public function handle(UpdateDeactivateProductCommand $command): Product
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateDeactivateProductUseCase->__invoke($command);
    }

}
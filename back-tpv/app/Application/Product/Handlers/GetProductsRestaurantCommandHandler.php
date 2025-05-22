<?php
// UpdateEmployeeHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Product\DTO\GetProductRestaurantCommand;
use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Product\UseCases\GetProductsRestaurantUseCase;
use App\Application\Product\UseCases\ListProductsRestaurantUseCase;
use App\Domain\Product\Entities\Product;
use Illuminate\Support\Collection;

class GetProductsRestaurantCommandHandler
{
    private GetProductsRestaurantUseCase $getProductsRestaurantUseCase;
    public function __construct(GetProductsRestaurantUseCase $getProductsRestaurantUseCase)
    {
        $this->getProductsRestaurantUseCase = $getProductsRestaurantUseCase;
    }

    public function handle(GetProductRestaurantCommand $command): UpdateProductRestaurantCommand
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->getProductsRestaurantUseCase->__invoke($command);
    }

}
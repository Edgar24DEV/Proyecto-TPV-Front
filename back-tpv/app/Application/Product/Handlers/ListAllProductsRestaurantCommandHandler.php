<?php
// UpdateEmployeeHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\UseCases\ListAllProductsRestaurantUseCase;
use App\Application\Product\UseCases\ListProductsRestaurantUseCase;
use Illuminate\Support\Collection;

class ListAllProductsRestaurantCommandHandler
{
    private ListAllProductsRestaurantUseCase $listProductsRestaurantUseCase;
    public function __construct(ListAllProductsRestaurantUseCase $listProductsRestaurantUseCase)
    {
        $this->listProductsRestaurantUseCase = $listProductsRestaurantUseCase;
    }

    public function handle(ListProductsRestaurantCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listProductsRestaurantUseCase->__invoke($command);
    }

}
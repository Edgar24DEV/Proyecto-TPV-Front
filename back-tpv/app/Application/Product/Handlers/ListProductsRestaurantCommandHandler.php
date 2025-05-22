<?php
// UpdateEmployeeHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\UseCases\ListProductsRestaurantUseCase;
use Illuminate\Support\Collection;

class ListProductsRestaurantCommandHandler
{
    private ListProductsRestaurantUseCase $listProductsRestaurantUseCase;
    public function __construct(ListProductsRestaurantUseCase $listProductsRestaurantUseCase)
    {
        $this->listProductsRestaurantUseCase = $listProductsRestaurantUseCase;
    }

    public function handle(ListProductsRestaurantCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listProductsRestaurantUseCase->__invoke($command);
    }

}
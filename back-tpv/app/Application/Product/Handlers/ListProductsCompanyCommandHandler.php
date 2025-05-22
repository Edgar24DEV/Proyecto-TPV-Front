<?php
// UpdateEmployeeHandler.php
namespace App\Application\Product\Handlers;

use App\Application\Product\DTO\ListProductsCompanyCommand;
use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\UseCases\ListProductsCompanyUseCase;
use App\Application\Product\UseCases\ListProductsRestaurantUseCase;
use Illuminate\Support\Collection;

class ListProductsCompanyCommandHandler
{
    private ListProductsCompanyUseCase $listProductsCompanyUseCase;
    public function __construct(ListProductsCompanyUseCase $listProductsCompanyUseCase)
    {
        $this->listProductsCompanyUseCase = $listProductsCompanyUseCase;
    }

    public function handle(ListProductsCompanyCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listProductsCompanyUseCase->__invoke($command);
    }

}
<?php
namespace App\Application\Product\UseCases;
use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListAllProductsRestaurantUseCase
{



    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentProductRepository $productRepository,
        private readonly ProductService $productService,
    ) {
    }
    public function __invoke(ListproductsRestaurantCommand $command): Collection
    {
        $idRestaurant = $command->getIdRestaurant();
        $this->validateOrFail($idRestaurant);
        $products = $this->productRepository->findAll($idRestaurant);
        $products = $this->productService->showProductInfo($products);
        return $products;
    }


    private function validateOrFail(int $idRestaurant): void
    {
        if ($idRestaurant <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->restaurantRepository->exist($idRestaurant)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}
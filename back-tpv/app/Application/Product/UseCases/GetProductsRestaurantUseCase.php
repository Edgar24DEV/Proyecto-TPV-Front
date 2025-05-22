<?php
namespace App\Application\Product\UseCases;
use App\Application\Product\DTO\GetProductRestaurantCommand;
use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class GetProductsRestaurantUseCase
{



    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentProductRepository $productRepository,
        private readonly ProductService $productService,
    ) {
    }
    public function __invoke(GetProductRestaurantCommand $command): UpdateProductRestaurantCommand
    {
        // 1. Validar datos del comando
        $this->validateCommand($command);

        // 2. Verificar existencia de dependencias
        $this->checkDependenciesExist($command);

        // 4. Persistir el producto
        $product = $this->productRepository->getProductRestaurant($command);

        return $product;
    }

    private function validateCommand(GetProductRestaurantCommand $command): void
    {

        if ($command->getIdProducto() === null) {
            throw new \InvalidArgumentException('El producto es obligatorio');
        }
    }

    private function checkDependenciesExist(GetProductRestaurantCommand $command): void
    {

        if (!$this->restaurantRepository->exist($command->getIdRestaurante())) {
            throw new \InvalidArgumentException('El restaurante especificado no existe');
        }

        if (!$this->productRepository->exist($command->getIdProducto())) {
            throw new \InvalidArgumentException('El producto no existe');
        }
    }

}
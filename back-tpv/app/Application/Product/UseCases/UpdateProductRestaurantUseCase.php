<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;

class UpdateProductRestaurantUseCase
{
    public function __construct(
        private readonly EloquentProductRepository $productRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
    ) {
    }

    public function __invoke(UpdateProductRestaurantCommand $command): Product
    {
        // 1. Validar datos del comando
        $this->validateCommand($command);

        // 2. Verificar existencia de dependencias
        $this->checkDependenciesExist($command);

        // 4. Persistir el producto
        $product = $this->productRepository->updateProductRestaurant($command);

        return $product;
    }

    private function validateCommand(UpdateProductRestaurantCommand $command): void
    {

        if ($command->getIdProducto() === null) {
            throw new \InvalidArgumentException('El producto es obligatorio');
        }
    }

    private function checkDependenciesExist(UpdateProductRestaurantCommand $command): void
    {

        if (!$this->restaurantRepository->exist($command->getIdRestaurante())) {
            throw new \InvalidArgumentException('El restaurante especificado no existe');
        }

        if (!$this->productRepository->exist($command->getIdProducto())) {
            throw new \InvalidArgumentException('El producto no existe');
        }
    }
}
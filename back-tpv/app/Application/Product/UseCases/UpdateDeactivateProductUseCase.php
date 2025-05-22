<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTO\UpdateDeactivateProductCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;


class UpdateDeactivateProductUseCase
{
    public function __construct(
        private readonly EloquentProductRepository $productRepository,
    ) {
    }

    public function __invoke(UpdateDeactivateProductCommand $command): Product
    {
        // 1. Validar datos del comando
        $this->validateCommand($command);

        // 2. Verificar existencia de dependencias
        $this->checkDependenciesExist($command);

        // 4. Persistir el producto
        $product = $this->productRepository->updateProductDeactivate($command);

        return $product;
    }

    private function validateCommand(UpdateDeactivateProductCommand $command): void
    {

        if ($command->getIdProducto() === null) {
            throw new \InvalidArgumentException('El producto es obligatorio');
        }
    }

    private function checkDependenciesExist(UpdateDeactivateProductCommand $command): void
    {

        if (!$this->productRepository->exist($command->getIdProducto())) {
            throw new \InvalidArgumentException('El producto no existe');
        }

    }
}
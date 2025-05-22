<?php

namespace App\Application\Restaurant\UseCases;

use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Restaurant\DTO\AddRestaurantProductCommand;
use App\Application\Restaurant\DTO\UpdateRestaurantProductCommand;
use App\Domain\Product\Entities\Product;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;

class AddRestaurantProductUseCase
{
    public function __construct(
        private readonly EloquentProductRepository $productRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository
    ) {
    }

    public function __invoke(AddRestaurantProductCommand $command): AddRestaurantProductCommand | Product
    {

        $this->checkDependenciesExist($command);
        $existProductRestaurant =$this->productRepository->findByProductAndRestaurant($command->getIdProducto(), $command->getIdRestaurante());

        if($existProductRestaurant){
            $updateCommand = new UpdateProductRestaurantCommand(
                activo: $command->getActivo(),
                idRestaurante: $command->getIdRestaurante(),
                idProducto: $command->getIdProducto(),
            );

            $updateRelation = $this->productRepository->updateProductRestaurant(command: $updateCommand);

            return $updateRelation;

        }

        
        $product = $this->restaurantRepository->saveRestaurantProduct(command: $command);
        return $product;
    }


    private function checkDependenciesExist(AddRestaurantProductCommand $command): void
    {
        if (!$this->restaurantRepository->exist($command->getIdRestaurante())) {
            throw new \InvalidArgumentException('El restaurante especificado no existe');
        }

        if (!$this->productRepository->exist($command->getIdProducto())) {
            throw new \InvalidArgumentException('Este producto no existe en la base de datos');
        }
    }
}
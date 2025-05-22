<?php

namespace App\Application\Product\Handlers;

use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\DTO\AddProductRestaurantCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Product\UseCases\AddProductRestaurantUseCase;
use App\Application\Product\UseCases\AddProductUseCase;
use App\Application\Product\UseCases\UpdateProductRestaurantUseCase;
use App\Domain\Product\Entities\Product;

class UpdateProductRestaurantCommandHandler
{
    private UpdateProductRestaurantUseCase $updateProductRestaurantUseCase;

    public function __construct(UpdateProductRestaurantUseCase $updateProductRestaurantUseCase)
    {
        $this->updateProductRestaurantUseCase = $updateProductRestaurantUseCase;
    }

    public function handle(UpdateProductRestaurantCommand $command): Product
    {
        return $this->updateProductRestaurantUseCase->__invoke($command);
    }
}
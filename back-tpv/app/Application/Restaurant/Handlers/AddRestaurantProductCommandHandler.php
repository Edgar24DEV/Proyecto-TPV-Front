<?php

namespace App\Application\Restaurant\Handlers;

use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\UseCases\AddProductUseCase;
use App\Application\Restaurant\UseCases\AddRestaurantProductUseCase;
use App\Application\Restaurant\DTO\AddRestaurantProductCommand;
use App\Domain\Product\Entities\Product;

class AddRestaurantProductCommandHandler
{
    private AddRestaurantProductUseCase $addRestaurantProductUseCase;

    public function __construct(AddRestaurantProductUseCase $addRestaurantProductUseCase)
    {
        $this->addRestaurantProductUseCase = $addRestaurantProductUseCase;
    }

    public function handle(AddRestaurantProductCommand $command): AddRestaurantProductCommand | Product
    {
        return $this->addRestaurantProductUseCase->__invoke($command);
    }
}
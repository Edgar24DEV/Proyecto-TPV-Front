<?php

namespace App\Application\Product\Handlers;

use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\UseCases\AddProductUseCase;
use App\Domain\Product\Entities\Product;

class AddProductCommandHandler
{
    private AddProductUseCase $addProductUseCase;

    public function __construct(AddProductUseCase $addProductUseCase)
    {
        $this->addProductUseCase = $addProductUseCase;
    }

    public function handle(AddProductCommand $command): Product
    {
        return $this->addProductUseCase->__invoke($command);
    }
}
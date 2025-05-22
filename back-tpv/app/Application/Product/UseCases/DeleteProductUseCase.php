<?php

namespace App\Application\Product\UseCases;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Product\DTO\DeleteProductCommand;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class DeleteProductUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentProductRepository $productRepository,
    ) {
    }
    public function __invoke(DeleteProductCommand $command): bool
    {
        $this->validateOrFail($command->getId());
        $respuesta = $this->productRepository->delete($command->getId());
        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->productRepository->exist($id)) {
            throw new \InvalidArgumentException("ID de pedido inválido");
        }
    }
}
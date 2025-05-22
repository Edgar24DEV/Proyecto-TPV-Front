<?php

namespace App\Application\Product\UseCases;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Product\DTO\DeleteProductCommand;
use App\Application\Product\DTO\GetProductCommand;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetProductByIdUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentProductRepository $productRepository,
    ) {
    }
    public function __invoke(GetProductCommand $command): Product
    {
        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->productRepository->findById($command->getId());
        } catch (\Exception $e) {

            return new Product(
                id: -1,
                nombre: "Producto no encontrado",
                precio: 0.0,
                imagen: null,
                activo: false,
                iva: 0.0,
                idCategoria: null,
                idEmpresa: null
            );
        }
        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->productRepository->exist($id)) {
            throw new \Exception("ID de producto inválido");
        }
    }
}
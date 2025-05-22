<?php

namespace App\Application\Product\UseCases;

use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Application\Product\DTO\UpdateProductCommand;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class UpdateProductUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentProductRepository $productRespository,
        private readonly ProductService $productService,
    ) {
    }
    public function __invoke(UpdateProductCommand $command): Product
    {
        $this->validateOrFail(
            $command->getId(),
        );

        // $employee = $this->employeeService->requestEmployee($command);
        try {
            $product = $this->productRespository->update($command);
        } catch (\Exception $e) {
            $productVacio = new Product(
                id: -1,
                nombre: "",
                precio: -1,
                imagen: $e,
                activo: false,
                iva: -1,
                idCategoria: -1,
                idEmpresa: -1
            );
            return $productVacio;
        }
        return $product;
    }


    private function validateOrFail(int $idOrder): void
    {
        if ($idOrder <= 0 || !$this->productRespository->exist($idOrder)) {
            throw new \InvalidArgumentException("ID de producto inválido");
        }
    }
}
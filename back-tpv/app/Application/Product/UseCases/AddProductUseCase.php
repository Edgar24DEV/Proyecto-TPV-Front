<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTO\AddProductCommand;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Repositories\CategoryRepositoryInterface;
use App\Domain\Company\Repositories\CompanyRepositoryInterface;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;

class AddProductUseCase
{
    public function __construct(
        private readonly EloquentProductRepository $productRepository,
        private readonly EloquentCategoryRepository $categoryRepository,
        private readonly EloquentCompanyRepository $companyRepository
    ) {
    }

    public function __invoke(AddProductCommand $command): Product
    {
        // 1. Validar datos del comando
        $this->validateCommand($command);

        // 2. Verificar existencia de dependencias
        $this->checkDependenciesExist($command);

        // 3. Crear el nuevo producto
        $product = new Product(
            id: null,
            nombre: $command->getNombre(),
            precio: $command->getPrecio(),
            imagen: $command->getImagen(),
            activo: true,
            iva: $command->getIva() ?? 10.0,
            idCategoria: $command->getIdCategoria(),
            idEmpresa: $command->getIdEmpresa()
        );

        // 4. Persistir el producto
        $product = $this->productRepository->save($product);

        return $product;
    }

    private function validateCommand(AddProductCommand $command): void
    {
        if (empty($command->getNombre())) {
            throw new \InvalidArgumentException('El nombre del producto es obligatorio');
        }

        if ($command->getPrecio() === null || $command->getPrecio() < 0) {
            throw new \InvalidArgumentException('El precio debe ser un valor positivo');
        }

        if ($command->getIdCategoria() === null) {
            throw new \InvalidArgumentException('La categoría es obligatoria');
        }

        if ($command->getIdEmpresa() === null) {
            throw new \InvalidArgumentException('La empresa es obligatoria');
        }
    }

    private function checkDependenciesExist(AddProductCommand $command): void
    {
        if (!$this->categoryRepository->exist($command->getIdCategoria())) {
            throw new \InvalidArgumentException('La categoría especificada no existe');
        }

        if (!$this->companyRepository->exist($command->getIdEmpresa())) {
            throw new \InvalidArgumentException('La empresa especificada no existe');
        }

        if ($this->productRepository->notEqual($command->getNombre(), $command->getIdEmpresa())) {
            throw new \InvalidArgumentException('Ya existe un producto con ese nombre');
        }
    }
}
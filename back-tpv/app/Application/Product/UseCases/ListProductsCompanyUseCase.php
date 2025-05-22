<?php
namespace App\Application\Product\UseCases;
use App\Application\Product\DTO\ListProductsCompanyCommand;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListProductsCompanyUseCase
{



    public function __construct(
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly EloquentProductRepository $productRepository,
        private readonly ProductService $productService,
    ) {
    }
    public function __invoke(ListProductsCompanyCommand $command): Collection
    {
        $idEmpresa = $command->getIdEmpresa();
        $this->validateOrFail($idEmpresa);
        $products = $this->productRepository->findByCompany($idEmpresa);
        $products = $this->productService->showProductInfo($products);
        return $products;
    }


    private function validateOrFail(int $idEmpresa): void
    {
        if ($idEmpresa <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->companyRepository->exist($idEmpresa)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}
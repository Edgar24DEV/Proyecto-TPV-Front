<?php

namespace App\Application\Category\UseCases;

use App\Application\Category\DTO\UpdateCategoryCommand;
use App\Domain\Product\Entities\Category;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class UpdateCategoryUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentCategoryRepository $categoryRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly CategoryService $categoryService,
    ) {
    }
    public function __invoke(UpdateCategoryCommand $command): Category
    {
        $this->validateOrFail(
            $command->getId(),
            $command->getIdEmpresa(),
        );

        // $category = $this->categoryService->requestCategory($command);
        try {
            $category = $this->categoryRepository->update($command);
            $categoryInfo = $this->categoryService->showCategoryInfoSimple($category);
        } catch (\Exception $e) {

            $categoryVacio = new Category(
                id: -1,
                categoria: "",
                activo: 0,
                idEmpresa: null,
            );
            return $categoryVacio;
        }
        return $categoryInfo;
    }


    private function validateOrFail(int $idCategoria, int $idEmpresa): void
    {
        if ($idCategoria <= 0 || !$this->categoryRepository->exist($idCategoria)) {
            throw new \Exception("ID categoria inválido");
        }
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \Exception("ID empresa inválido");
        }
    }
}

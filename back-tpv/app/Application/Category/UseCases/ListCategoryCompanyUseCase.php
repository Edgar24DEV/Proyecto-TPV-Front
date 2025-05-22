<?php
namespace App\Application\Category\UseCases;

use App\Application\Category\DTO\ListCategoryCommand;

use App\Application\Category\DTO\ListCategoryCompanyCommand;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListCategoryCompanyUseCase
{



    public function __construct(
        private readonly EloquentCategoryRepository $categoryRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly CategoryService $categoryService,
    ) {
    }
    public function __invoke(ListCategoryCompanyCommand $command): Collection
    {
        $id = $command->getId();
        $this->validateOrFail($id);
        $categories = $this->categoryRepository->findByCompany($id);
        $categories = $this->categoryService->showCategoryInfo($categories);
        return $categories;
    }


    private function validateOrFail(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }
        if (!$this->companyRepository->exist($id)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}
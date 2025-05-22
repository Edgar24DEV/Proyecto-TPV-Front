<?php
namespace App\Application\Category\UseCases;

use App\Application\Category\DTO\GetCategoryCompanyCommand;
use App\Application\Category\DTO\ListCategoryCommand;

use App\Application\Category\DTO\ListCategoryCompanyCommand;
use App\Domain\Product\Entities\Category;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class GetCategoryCompanyUseCase
{



    public function __construct(
        private readonly EloquentCategoryRepository $categoryRepository,
        private readonly CategoryService $categoryService,
    ) {
    }
    public function __invoke(GetCategoryCompanyCommand $command): Category
    {
        $id = $command->getId();
        $this->validateOrFail($id);
        $categories = $this->categoryRepository->getCategory($id);
        $categories = $this->categoryService->showCategoryInfoSimple($categories);
        return $categories;
    }


    private function validateOrFail(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->categoryRepository->exist($id)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}
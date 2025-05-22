<?php
namespace App\Application\Category\UseCases;

use App\Application\Category\DTO\ListCategoryCommand;

use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListCategoryUseCase
{



    public function __construct(
        private readonly EloquentCategoryRepository $categoryRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly CategoryService $categoryService,
    ) {
    }
    public function __invoke(ListCategoryCommand $command): Collection
    {
        $idRestaurant = $command->getIdRestaurant();
        $this->validateOrFail($idRestaurant);
        $categories = $this->categoryRepository->find($idRestaurant);
        $categories = $this->categoryService->showCategoryInfo($categories);
        return $categories;
    }


    private function validateOrFail(int $idRestaurant): void
    {
        if ($idRestaurant <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->restaurantRepository->exist($idRestaurant)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}
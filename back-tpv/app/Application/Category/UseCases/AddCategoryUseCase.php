<?php

namespace App\Application\Category\UseCases;

use App\Application\Category\DTO\AddCategoryCommand;
use App\Domain\Product\Entities\Category;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class AddCategoryUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentCategoryRepository $categoryRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly CategoryService $categoryService,
    ) {}
    public function __invoke(AddCategoryCommand $command): Category
    {

        $this->validateOrFail(
            $command->getIdEmpresa(),
            $command->getCategoria()
        );

        try {
            $category = $this->categoryRepository->create($command);
            $categoryInfo = $this->categoryService->showCategoryInfoSimple($category);
        } catch (\Exception $e) {

            Log::channel('product')->error("Error añadiendo categoría}\n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                ->take(3)
                ->map(function ($trace, $i) {
                    return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                })
                ->implode("\n") . "\n");

            $categoryVacio = new Category(
                id: -1,
                categoria: "",
                activo: false,
                idEmpresa: null,
            );
            return $categoryVacio;
        }
        return $categoryInfo;
    }


    private function validateOrFail($idEmpresa, $categoria): void
    {
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \Exception("ID empresa inválido");
        }
        if ($this->categoryRepository->existCategory($categoria, $idEmpresa) || !$categoria) {
            throw new \Exception("Ya existe la categoria");
        }
    }
}

<?php
// UpdateCategoryHandler.php
namespace App\Application\Category\Handlers;

use App\Application\Category\DTO\UpdateActiveCategoryCommand;
use App\Application\Category\UseCases\UpdateActiveCategoryUseCase;
use App\Domain\Product\Entities\Category;

class UpdateActiveCategoryCommandHandler
{
    private UpdateActiveCategoryUseCase $updateActiveCategoryUseCase;


    public function __construct(UpdateActiveCategoryUseCase $updateActiveCategoryUseCase)
    {
        $this->updateActiveCategoryUseCase = $updateActiveCategoryUseCase;
    }

    public function handle(UpdateActiveCategoryCommand $command): Category
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateActiveCategoryUseCase->__invoke($command);
    }

    private function isCategoryAllowedToUpdate(int $categoryId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}
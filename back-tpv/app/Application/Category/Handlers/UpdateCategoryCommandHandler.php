<?php
// UpdateCategoryHandler.php
namespace App\Application\Category\Handlers;

use App\Application\Category\DTO\UpdateCategoryCommand;
use App\Application\Category\UseCases\UpdateCategoryUseCase;
use App\Domain\Product\Entities\Category;

class UpdateCategoryCommandHandler
{
    private UpdateCategoryUseCase $updateCategoryUseCase;


    public function __construct(UpdateCategoryUseCase $updateCategoryUseCase)
    {
        $this->updateCategoryUseCase = $updateCategoryUseCase;
    }

    public function handle(UpdateCategoryCommand $command): Category
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateCategoryUseCase->__invoke($command);
    }

    private function isCategoryAllowedToUpdate(int $categoryId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}
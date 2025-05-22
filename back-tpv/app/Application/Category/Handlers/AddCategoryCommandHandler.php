<?php
// UpdateCategoryHandler.php
namespace App\Application\Category\Handlers;

use App\Application\Category\DTO\AddCategoryCommand;
use App\Application\Category\DTO\UpdateCategoryCommand;
use App\Application\Category\DTO\UpdateCategoryDTO;
use App\Application\Category\UseCases\AddCategoryUseCase;
use App\Domain\Product\Entities\Category;
use App\Domain\Audit\Services\AuditService;

class AddCategoryCommandHandler
{
    private AddCategoryUseCase $addCategoryUseCase;


    public function __construct(AddCategoryUseCase $addCategoryUseCase)
    {
        $this->addCategoryUseCase = $addCategoryUseCase;
    }

    public function handle(AddCategoryCommand $command): Category
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->addCategoryUseCase->__invoke($command);
    }
/*
    private function isCategoryAllowedToUpdate(int $categoryId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }*/
}
<?php
namespace App\Application\Category\Handlers;

use App\Application\Category\DTO\DeleteCategoryCommand;
use App\Application\Category\UseCases\DeleteCategoryUseCase;


class DeleteCategoryCommandHandler
{
    private DeleteCategoryUseCase $deleteCategoryUseCase;


    public function __construct(DeleteCategoryUseCase $deleteCategoryUseCase)
    {
        $this->deleteCategoryUseCase = $deleteCategoryUseCase;
    }

    public function handle(DeleteCategoryCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteCategoryUseCase->__invoke($command);
    }

}
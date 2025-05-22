<?php

namespace App\Application\Category\UseCases;

use App\Application\Category\DTO\DeleteCategoryCommand;
use App\Infrastructure\Repositories\EloquentCategoryRepository;

use Illuminate\Support\Facades\Log;

class DeleteCategoryUseCase
{
    public function __construct(
        private readonly EloquentCategoryRepository $categoryRepository,
    ) {
    }
    public function __invoke(DeleteCategoryCommand $command): bool
    {
        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->categoryRepository->delete($command->getId());
        } catch (\Exception $e) {

            return false;
        }
        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->categoryRepository->exist($id)) {
            throw new \Exception("ID categoría inválido");
        }
    }
}
<?php

namespace App\Application\Location\UseCases;

use App\Application\Location\DTO\DeleteLocationCommand;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class DeleteLocationUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentLocationRepository $locationRepository,
    ) {
    }
    public function __invoke(DeleteLocationCommand $command): bool
    {
        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->locationRepository->delete($command->getId());
        } catch (\Exception $e) {
            return $respuesta;
        }
        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->locationRepository->exist($id)) {
            throw new \InvalidArgumentException("ID ubicacion inválido");
        }
    }
}
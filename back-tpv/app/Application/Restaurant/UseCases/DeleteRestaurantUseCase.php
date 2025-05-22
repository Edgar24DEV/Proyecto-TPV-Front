<?php

namespace App\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\DeleteRestaurantCommand;
use App\Application\Restaurant\DTO\FindRestaurantCifCommand;
use App\Domain\Company\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;

class DeleteRestaurantUseCase
{
    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly RestaurantService $restaurantService
    ) {}

    public function __invoke(DeleteRestaurantCommand $command)   
    {
        $restaurant = $this->restaurantRepository->find($command->getId());

        if (!$restaurant) {
            throw new \Exception("Restaurante no encontrado.");
        }

        return $this->restaurantRepository->softDelete($command->getId());
    }
}

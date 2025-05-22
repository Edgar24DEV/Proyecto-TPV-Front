<?php

namespace App\Application\Restaurant\Handlers;

use App\Application\Restaurant\DTO\DeleteRestaurantCommand;
use App\Application\Restaurant\UseCases\DeleteRestaurantUseCase;

class DeleteRestaurantCommandHandler
{
    private  $deleteRestaurantUseCase;

    public function __construct(DeleteRestaurantUseCase $deleteRestaurantUseCase)
    {
        $this->deleteRestaurantUseCase = $deleteRestaurantUseCase;
    }

    public function handle(DeleteRestaurantCommand $command)
    {
        return $this->deleteRestaurantUseCase->__invoke($command);
    }
}

<?php

namespace App\Application\Restaurant\Handlers;

use App\Application\Restaurant\DTO\FindRestaurantCommand;
use App\Application\Restaurant\UseCases\FindRestaurantUseCase;

class FindRestaurantCommandHandler
{
    private  $findRestaurantUseCase;

    public function __construct(FindRestaurantUseCase $findRestaurantUseCase)
    {
        $this->findRestaurantUseCase = $findRestaurantUseCase;
    }

    public function handle(FindRestaurantCommand $command)
    {
        return $this->findRestaurantUseCase->__invoke($command);
    }
}

<?php
namespace App\Application\Restaurant\Handlers;

use App\Application\Restaurant\DTO\AddRestaurantCommand;
use App\Application\Restaurant\UseCases\AddRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;

class AddRestaurantCommandHandler
{
    private AddRestaurantUseCase $addRestaurantUseCase;


    public function __construct(AddRestaurantUseCase $addRestaurantUseCase)
    {
        $this->addRestaurantUseCase = $addRestaurantUseCase;
    }

    public function handle(AddRestaurantCommand $command): Restaurant
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->addRestaurantUseCase->__invoke($command);
    }

}

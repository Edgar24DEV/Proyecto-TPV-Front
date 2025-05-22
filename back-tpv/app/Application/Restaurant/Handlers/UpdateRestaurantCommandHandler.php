<?php
namespace App\Application\Restaurant\Handlers;

use App\Application\Restaurant\DTO\UpdateRestaurantCommand;
use App\Application\Restaurant\UseCases\UpdateRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;

class UpdateRestaurantCommandHandler
{
    private UpdateRestaurantUseCase $updateRestaurantUseCase;


    public function __construct(UpdateRestaurantUseCase $updateRestaurantUseCase)
    {
        $this->updateRestaurantUseCase = $updateRestaurantUseCase;
    }

    public function handle(UpdateRestaurantCommand $command): Restaurant
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateRestaurantUseCase->__invoke($command);
    }

}

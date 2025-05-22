<?php
// UpdateEmployeeHandler.php
namespace App\Application\Location\Handlers;

use App\Application\Location\DTO\ListLocationsRestaurantCommand;
use App\Application\Location\UseCases\ListLocationsRestaurantUseCase;
use Illuminate\Support\Collection;

class ListLocationsRestaurantCommandHandler
{
    private ListLocationsRestaurantUseCase $listLocationsRestaurantUseCase;
    public function __construct(ListLocationsRestaurantUseCase $listLocationsRestaurantUseCase)
    {
        $this->listLocationsRestaurantUseCase = $listLocationsRestaurantUseCase;
    }

    public function handle(ListLocationsRestaurantCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listLocationsRestaurantUseCase->__invoke($command);
    }

}
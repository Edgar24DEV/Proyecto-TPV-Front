<?php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\ListEmployeeRestaurantsCommand;
use App\Application\Employee\UseCases\ListEmployeeRestaurantsUseCase;
use Illuminate\Support\Collection;

class ListEmployeeRestaurantsCommandHandler
{
    private ListEmployeeRestaurantsUseCase $listEmployeeRestaurantsUseCase;
    public function __construct(ListEmployeeRestaurantsUseCase $listEmployeeRestaurantsUseCase)
    {
        $this->listEmployeeRestaurantsUseCase = $listEmployeeRestaurantsUseCase;
    }

    public function handle(ListEmployeeRestaurantsCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listEmployeeRestaurantsUseCase->__invoke($command);
    }

}
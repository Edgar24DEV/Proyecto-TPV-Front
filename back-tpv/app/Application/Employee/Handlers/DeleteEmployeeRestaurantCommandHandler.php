<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\DeleteEmployeeRestaurantCommand;
use App\Application\Employee\UseCases\DeleteEmployeeRestaurantUseCase;



class DeleteEmployeeRestaurantCommandHandler
{
    private DeleteEmployeeRestaurantUseCase $deleteEmployeeRestaurantUseCase;


    public function __construct(DeleteEmployeeRestaurantUseCase $deleteEmployeeRestaurantUseCase)
    {
        $this->deleteEmployeeRestaurantUseCase = $deleteEmployeeRestaurantUseCase;
    }

    public function handle(DeleteEmployeeRestaurantCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteEmployeeRestaurantUseCase->__invoke($command);
    }

}
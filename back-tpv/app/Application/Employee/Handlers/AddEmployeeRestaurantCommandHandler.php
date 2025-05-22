<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\AddEmployeeRestaurantCommand;
use App\Application\Employee\UseCases\AddEmployeeRestaurantUseCase;



class AddEmployeeRestaurantCommandHandler
{
    private AddEmployeeRestaurantUseCase $addEmployeeRestaurantUseCase;


    public function __construct(AddEmployeeRestaurantUseCase $addEmployeeRestaurantUseCase)
    {
        $this->addEmployeeRestaurantUseCase = $addEmployeeRestaurantUseCase;
    }

    public function handle(AddEmployeeRestaurantCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->addEmployeeRestaurantUseCase->__invoke($command);
    }

}
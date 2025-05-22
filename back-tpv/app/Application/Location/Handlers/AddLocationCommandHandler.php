<?php
namespace App\Application\Location\Handlers;

use App\Application\Location\DTO\AddLocationCommand;
use App\Application\Location\UseCases\AddLocationUseCase;
use App\Domain\Restaurant\Entities\Location;

class AddLocationCommandHandler
{
    private AddLocationUseCase $addLocationUseCase;


    public function __construct(AddLocationUseCase $addLocationUseCase)
    {
        $this->addLocationUseCase = $addLocationUseCase;
    }

    public function handle(AddLocationCommand $command): Location
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->addLocationUseCase->__invoke($command);
    }
/*
    private function isLocationAllowedToUpdate(int $LocationId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }*/
}
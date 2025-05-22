<?php
// UpdateLocationHandler.php
namespace App\Application\Location\Handlers;

use App\Application\Location\DTO\UpdateLocationCommand;
use App\Application\Location\UseCases\UpdateLocationUseCase;
use App\Domain\Restaurant\Entities\Location;

class UpdateLocationCommandHandler
{
    private UpdateLocationUseCase $updateLocationUseCase;


    public function __construct(UpdateLocationUseCase $updateLocationUseCase)
    {
        $this->updateLocationUseCase = $updateLocationUseCase;
    }

    public function handle(UpdateLocationCommand $command): Location
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateLocationUseCase->__invoke($command);
    }
}
<?php
// UpdateLocationHandler.php
namespace App\Application\Location\Handlers;

use App\Application\Location\DTO\UpdateActiveLocationCommand;
use App\Application\Location\UseCases\UpdateActiveLocationUseCase;
use App\Domain\Restaurant\Entities\Location;

class UpdateActiveLocationCommandHandler
{
    private UpdateActiveLocationUseCase $updateActiveLocationUseCase;


    public function __construct(UpdateActiveLocationUseCase $updateActiveLocationUseCase)
    {
        $this->updateActiveLocationUseCase = $updateActiveLocationUseCase;
    }

    public function handle(UpdateActiveLocationCommand $command): Location
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateActiveLocationUseCase->__invoke($command);
    }
}
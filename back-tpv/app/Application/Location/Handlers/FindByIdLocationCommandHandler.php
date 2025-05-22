<?php
// UpdateLocationHandler.php
namespace App\Application\Location\Handlers;

use App\Application\Location\DTO\FindByIdLocationCommand;
use App\Application\Location\UseCases\FindByIdLocationUseCase;
use App\Domain\Restaurant\Entities\Location;

class FindByIdLocationCommandHandler
{
    private FindByIdLocationUseCase $findByIdLocationUseCase;
    public function __construct(FindByIdLocationUseCase $findByIdLocationUseCase)
    {
        $this->findByIdLocationUseCase = $findByIdLocationUseCase;
    }

    public function handle(FindByIdLocationCommand $command): Location
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->findByIdLocationUseCase->__invoke($command);
    }

}
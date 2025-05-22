<?php
// UpdateLocationHandler.php
namespace App\Application\Location\Handlers;

use App\Application\Location\DTO\DeleteLocationCommand;
use App\Application\Location\UseCases\DeleteLocationUseCase;


class DeleteLocationCommandHandler
{
    private DeleteLocationUseCase $deleteLocationUseCase;


    public function __construct(DeleteLocationUseCase $deleteLocationUseCase)
    {
        $this->deleteLocationUseCase = $deleteLocationUseCase;
    }

    public function handle(DeleteLocationCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteLocationUseCase->__invoke($command);
    }

}
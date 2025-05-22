<?php
// UpdateClientHandler.php
namespace App\Application\Client\Handlers;

use App\Application\Client\DTO\FindByIdClientCommand;
use App\Application\Client\UseCases\FindByIdClientUseCase;
use App\Domain\Company\Entities\Client;

class FindByIdClientCommandHandler
{
    private FindByIdClientUseCase $findByIdClientUseCase;
    public function __construct(FindByIdClientUseCase $findByIdClientUseCase)
    {
        $this->findByIdClientUseCase = $findByIdClientUseCase;
    }

    public function handle(FindByIdClientCommand $command): Client
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->findByIdClientUseCase->__invoke($command);
    }

}
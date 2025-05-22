<?php
// UpdateEmployeeHandler.php
namespace App\Application\Client\Handlers;

use App\Application\Client\DTO\ListClientCompanyCommand;
use App\Application\Client\UseCases\ListClientCompanyUseCase;

use Illuminate\Support\Collection;

class ListClientCompanyCommandHandler
{
    private ListClientCompanyUseCase $listClientCompanyUseCase;
    public function __construct(ListClientCompanyUseCase $listClientCompanyUseCase)
    {
        $this->listClientCompanyUseCase = $listClientCompanyUseCase ;
    }

    public function handle(ListClientCompanyCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listClientCompanyUseCase->__invoke($command);
    }

}
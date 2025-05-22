<?php
// UpdateEmployeeHandler.php
namespace App\Application\Role\Handlers;


use App\Application\Role\UseCases\ListRolesCompanyUseCase;
use App\Application\Role\DTO\ListRolesCompanyCommand;
use Illuminate\Support\Collection;

class ListRolesCompanyCommandHandler
{
    private ListRolesCompanyUseCase $listRolesCompanyUseCase;
    public function __construct(ListRolesCompanyUseCase $listRolesCompanyUseCase)
    {
        $this->listRolesCompanyUseCase = $listRolesCompanyUseCase;
    }

    public function handle(ListRolesCompanyCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listRolesCompanyUseCase->__invoke($command);
    }

}
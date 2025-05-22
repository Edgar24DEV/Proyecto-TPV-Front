<?php
// UpdateEmployeeHandler.php
namespace App\Application\Restaurant\Handlers;

use App\Application\Restaurant\DTO\ListRestaurantsCompanyCommand;
use App\Application\Restaurant\UseCases\ListRestaurantsCompanyUseCase;
use Illuminate\Support\Collection;

class ListRestaurantsCompanyCommandHandler
{
    private ListRestaurantsCompanyUseCase $listRestaurantsCompanyUseCase;
    public function __construct(ListRestaurantsCompanyUseCase $listRestaurantsCompanyUseCase)
    {
        $this->listRestaurantsCompanyUseCase = $listRestaurantsCompanyUseCase;
    }

    public function handle(ListRestaurantsCompanyCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listRestaurantsCompanyUseCase->__invoke($command);
    }

}
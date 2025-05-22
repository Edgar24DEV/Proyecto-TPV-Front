<?php
namespace App\Application\Restaurant\UseCases;
use App\Application\Restaurant\DTO\ListRestaurantsCompanyCommand;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListRestaurantsCompanyUseCase
{



    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly RestaurantService $restaurantService,
    ) {
    }
    public function __invoke(ListRestaurantsCompanyCommand $command): Collection
    {
        $idEmpresa = $command->getIdEmpresa();
        $this->validateOrFail($idEmpresa);
        $restaurants = $this->restaurantRepository->findByCompanyID($idEmpresa);
        $restaurants = $this->restaurantService->showRestaurantInfo($restaurants);
        return $restaurants;
    }


    private function validateOrFail(int $idEmpresa): void
    {
        if ($idEmpresa <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->companyRepository->exist($idEmpresa)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}
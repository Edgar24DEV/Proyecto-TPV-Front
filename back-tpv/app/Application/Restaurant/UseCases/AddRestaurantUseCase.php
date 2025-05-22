<?php

namespace App\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\AddRestaurantCommand;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class AddRestaurantUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly RestaurantService $restaurantService,
    ) {
    }
    public function __invoke(AddRestaurantCommand $command): Restaurant
    {

        $this->validateOrFail(
            $command->getIdEmpresa(),
            $command->getNombre(),
        );

        try {
            $restaurant = $this->restaurantRepository->create($command);
            $restaurantInfo = $this->restaurantService->showRestaurantInfoSimple($restaurant);
        } catch (\Exception $e) {
            dd($e);
            $restaurantVacio = new Restaurant(
                id: -1,
                nombre: "",
                direccion: "",
                telefono: "",
                contrasenya: "",
                direccionFiscal: "",
                cif: "",
                razonSocial: "",
                idEmpresa: null,
            );
            return $restaurantVacio;
        }
        return $restaurantInfo;
    }


    private function validateOrFail($idEmpresa, $name): void
    {
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \Exception("ID empresa inválido");
        }
        if ($this->restaurantRepository->existName($name)) {
            throw new \Exception("El restaurante ya existe");
        }
    }
}
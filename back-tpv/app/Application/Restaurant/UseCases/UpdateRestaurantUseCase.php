<?php

namespace App\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\UpdateRestaurantCommand;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class UpdateRestaurantUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly RestaurantService $restaurantService,
    ) {
    }
    public function __invoke(UpdateRestaurantCommand $command): Restaurant
    {

        $this->validateOrFail(
            $command->getIdEmpresa(),
            $command->getId(),
        );

        try {
            $restaurant = $this->restaurantRepository->update($command);
            $restaurantInfo = $this->restaurantService->showRestaurantInfoSimple($restaurant);
        } catch (\Exception $e) {
            Log::channel('restaurant')->error(
                "No se pudo actualizar el restaurante \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                    ->take(3)
                    ->map(function ($trace, $i) {
                        return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                    })
                    ->implode("\n") . "\n"
            );
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


    private function validateOrFail($idEmpresa, $idRestaurant): void
    {
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \Exception("ID empresa inválido");
        }
        if ($idRestaurant <= 0 || !$this->restaurantRepository->exist($idRestaurant)) {
            throw new \Exception("ID restuarante inválido");
        }
        if ($idRestaurant <= 0 || !$this->restaurantRepository->existwithCompany($idRestaurant, $idEmpresa)) {
            throw new \Exception("El restaurante no le correspondie a esa empresa");
        }
    }
}
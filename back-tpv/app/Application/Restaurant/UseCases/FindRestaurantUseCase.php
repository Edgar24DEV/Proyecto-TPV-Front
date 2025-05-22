<?php

namespace App\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\FindRestaurantCommand;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class FindRestaurantUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly RestaurantService $restaurantService,
    ) {
    }
    public function __invoke(FindRestaurantCommand $command): Restaurant
    {

        $this->validateOrFail(
            $command->getId(),
        );

        try {
            $restaurant = $this->restaurantRepository->findById($command->getId());
            $restaurantInfo = $this->restaurantService->showRestaurantInfoSimple($restaurant);
        } catch (\Exception $e) {
            Log::channel('restaurant')->error(
                "No se pudo encontrar el restaurante \n" .
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


    private function validateOrFail($idRestaurant): void
    {
        if ($idRestaurant <= 0 || !$this->restaurantRepository->exist($idRestaurant)) {
            throw new \Exception("ID restuarante inválido");
        }
    }
}
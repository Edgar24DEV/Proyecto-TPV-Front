<?php

namespace App\Application\Restaurant\UseCases;

use App\Application\Employee\DTO\LoginEmployeeCommand;
use App\Application\Restaurant\DTO\LoginRestaurantCommand;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

final class LoginRestaurantUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly RestaurantService $restaurantService,
    ) {
    }
    public function __invoke(LoginRestaurantCommand $command): Restaurant
    {


        try {
            $respuesta = $this->restaurantRepository->login($command);
            $restaurantInfo = $this->restaurantService->showRestaurantInfoSimple($respuesta);
        } catch (\Exception $e) {
            $restaurantVacio = new Restaurant(
                id: -1,
                nombre: null,
                direccion: null,
                telefono: null,
                contrasenya: null,
                direccionFiscal: null,
                cif: null,
                razonSocial: null,
                idEmpresa: null,
            );
            return $restaurantVacio;
        }
        return $restaurantInfo;
    }

}
<?php

namespace App\Application\Company\UseCases;

use App\Application\Company\DTO\LoginCompanyCommand;
use App\Domain\Company\Entities\Company;
use App\Domain\Company\Services\CompanyService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

final class LoginCompanyUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly CompanyService $companyService,
    ) {
    }
    public function __invoke(LoginCompanyCommand $command): Company
    {

        try {
            $respuesta = $this->companyRepository->login($command);
            $companyInfo = $this->companyService->showCompanyInfoSimple($respuesta);
        } catch (\Exception $e) {
            $companyVacio = new Company(
                id: -1,
                nombre: $e,
                direccionFiscal: "",
                CIF: "",
                razonSocial: "",
                telefono: "",
                correo: "",
                contrasenya: ""
            );
            return $companyVacio;
        }
        return $companyInfo;
    }
}

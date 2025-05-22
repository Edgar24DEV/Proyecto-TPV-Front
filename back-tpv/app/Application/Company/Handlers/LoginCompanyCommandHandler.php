<?php

namespace App\Application\Company\Handlers;

use App\Application\Company\DTO\LoginCompanyCommand;
use App\Application\Company\UseCases\LoginCompanyUseCase;
use App\Domain\Audit\Services\AuditService;
use App\Domain\Company\Entities\Company;


class LoginCompanyCommandHandler
{
    private LoginCompanyUseCase $loginCompanyUseCase;
    public function __construct(LoginCompanyUseCase $loginCompanyUseCase)
    {
        $this->loginCompanyUseCase = $loginCompanyUseCase;
    }

    public function handle(LoginCompanyCommand $command): Company
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->loginCompanyUseCase->__invoke($command);
    }

}
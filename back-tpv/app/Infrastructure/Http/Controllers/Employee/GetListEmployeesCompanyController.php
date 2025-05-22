<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\ListEmployeesCompanyCommand;
use App\Application\Employee\Handlers\ListEmployeesCompanyCommandHandler;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeesCompanyUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListEmployeesCompanyController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListEmployeesCompanyUseCase $listEmployeesCompanyUseCase,
        private readonly ListEmployeesCompanyCommandHandler $listEmployeesCompanyCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idCompany = $request->input("id_empresa");

            $this->isNanId($idCompany);

            $command = new ListEmployeesCompanyCommand(
                $idCompany
            );

            $result = $this->listEmployeesCompanyCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('employee')->error("Error listando empleados de la empresa ID: {$command->getIdEmpresa()}\n" .
            "   Clase: " . __CLASS__ . "\n" .
            "   Mensaje: " . $e->getMessage() . "\n" .
            "   Línea: " . $e->getLine() . "\n" .
            "   Trace:\n" . collect($e->getTrace())
            ->take(3)
            ->map(function ($trace, $i) {
                return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
            })
            ->implode("\n") . "\n");
            return $this->apiError("" . $e->getMessage());
        }
    }

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }
    }
}

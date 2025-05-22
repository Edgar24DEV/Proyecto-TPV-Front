<?php

namespace Controllers\Company;

use App\Application\Role\DTO\ListRolesCompanyCommand;
use App\Application\Role\Handlers\ListRolesCompanyCommandHandler;
use App\Application\Role\UseCases\ListRolesCompanyUseCase;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListRolesCompanyController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListRolesCompanyUseCase $listRolesCompanyUseCase,
        private readonly ListRolesCompanyCommandHandler $listRolesCompanyCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idEmpresa = $request->input("id_empresa");

            $this->isNanId($idEmpresa);

            $command = new ListRolesCompanyCommand(
                $idEmpresa
            );

            $result = $this->listRolesCompanyCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('company')->error("Error al listar todos los roles de la empresa: {$command->getIdEmpresa()} \n" .
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


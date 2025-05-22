<?php

namespace Controllers\Client;

use App\Application\Client\DTO\ListClientCompanyCommand;
use App\Application\Client\DTO\ListClientRestaurantCommand;
use App\Application\Client\Handlers\ListClientCompanyCommandHandler;
use App\Application\Client\Handlers\ListClientRestaurantCommandHandler;
use App\Application\Client\UseCases\ListClientCompanyUseCase;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListClientCompanyController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListClientCompanyUseCase $listClientCompanyUseCase,
        private readonly ListClientCompanyCommandHandler $listClientCompanyCommandHandler,
    ) {
    }

    public function __invoke(Request $request)
    {
        try {
            $idCompany = $request->input("id_empresa");

            $idCompany = (int) $idCompany;


            $this->isNanId((int) $idCompany);

            $command = new ListClientCompanyCommand(
                $idCompany
            );

            $result = $this->listClientCompanyCommandHandler->handle($command);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::channel('company')->error(
                "No se pudo encontrar el cliente de esta empresa \n" .
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

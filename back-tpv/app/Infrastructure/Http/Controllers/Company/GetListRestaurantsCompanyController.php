<?php

namespace Controllers\Company;

use App\Application\Restaurant\DTO\ListRestaurantsCompanyCommand;
use App\Application\Restaurant\Handlers\ListRestaurantsCompanyCommandHandler;
use App\Application\Restaurant\UseCases\ListRestaurantsCompanyUseCase;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListRestaurantsCompanyController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListRestaurantsCompanyUseCase $listRestaurantCompanyUseCase,
        private readonly ListRestaurantsCompanyCommandHandler $listRestaurantCompanyCommandHandler,
    ) {}

    public function __invoke(Request $request)
    {

        try {

            $idEmpresa = $request->input("id_empresa");

            $this->isNanId($idEmpresa);

            $command = new ListRestaurantsCompanyCommand(
                $idEmpresa
            );

            $result = $this->listRestaurantCompanyCommandHandler->handle($command);


            return response()->json($result);
        } catch (\Exception $e) {
            Log::channel('company')->error("Error al listar los restaurantes de la empresa {$command->getIdEmpresa()} \n" .
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

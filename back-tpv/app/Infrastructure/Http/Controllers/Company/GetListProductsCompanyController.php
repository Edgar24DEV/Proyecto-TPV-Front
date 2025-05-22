<?php

namespace Controllers\Company;

use App\Application\Product\DTO\ListProductsCompanyCommand;
use App\Application\Product\Handlers\ListProductsCompanyCommandHandler;
use App\Application\Product\UseCases\ListProductsCompanyUseCase;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListProductsCompanyController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListProductsCompanyUseCase $listProductsCompanyUseCase,
        private readonly ListProductsCompanyCommandHandler $listProductsCompanyCommandHandler,
    ) {}

    public function __invoke(Request $request)
    {

        try {

            $idEmpresa = $request->input("id_empresa");

            $this->isNanId($idEmpresa);

            $command = new ListProductsCompanyCommand(
                $idEmpresa
            );

            $result = $this->listProductsCompanyCommandHandler->handle($command);


            return response()->json($result);
        } catch (\Exception $e) {
            Log::channel('company')->error("Error al listar los productos de la empresa: {$command->getIdEmpresa()} \n" .
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
        /*
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }*/
    }
}

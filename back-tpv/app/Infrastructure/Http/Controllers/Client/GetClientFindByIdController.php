<?php

namespace Controllers\Client;

use App\Application\Client\DTO\FindByIdClientCommand;
use App\Application\Client\Handlers\FindByIdClientCommandHandler;
use App\Application\Client\UseCases\FindByIdClientUseCase;
use Illuminate\Http\Request;
use App\Application\Client\UseCases\ListClientsRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetClientFindByIdController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly FindByIdClientUseCase $findByIdClientUseCase,
        private readonly FindByIdClientCommandHandler $findByIdClientCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idClient = new FindByIdClientCommand(
                $request->input("id"),
            );

            $this->isNanId($idClient->getId());

            $result = $this->findByIdClientCommandHandler->handle($idClient);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('company')->error(
                "No se pudo encontrar el cliente \n" .
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

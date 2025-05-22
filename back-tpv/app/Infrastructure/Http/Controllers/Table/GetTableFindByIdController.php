<?php

namespace Controllers\Table;

use App\Application\Table\DTO\FindByIdTableCommand;
use App\Application\Table\Handlers\FindByIdTableCommandHandler;
use App\Application\Table\UseCases\FindByIdTableUseCase;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetTableFindByIdController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly FindByIdTableUseCase $findByIdTableUseCase,
        private readonly FindByIdTableCommandHandler $findByIdTableCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idTable = new FindByIdTableCommand(
                $request->input("id"),
            );

            $this->isNanId($idTable->getId());

            $result = $this->findByIdTableCommandHandler->handle($idTable);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('restaurant')->error("Error al encontrar  mesa \n" .
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

<?php

namespace Controllers\Table;

use App\Application\Table\DTO\DeleteTableCommand;
use App\Application\Table\Handlers\DeleteTableCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Table\UseCases\DeleteTableUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class DeleteTableController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteTableUseCase $deleteTableUseCase,
        private readonly DeleteTableCommandHandler $deleteTableCommandHandler,
    ) {}


    public function __invoke(Request $request): JsonResponse
    {
        $id = new DeleteTableCommand(
            $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $result = $this->deleteTableCommandHandler->handle($id);
            if (!$result) {
                return response()->json([
                    'error' =>  "Error al intentar borrar"
                ], 404);
            }
            return response()->json("Borrado con exito");
        } catch (\InvalidArgumentException $e) {
            Log::channel('restaurant')->error("Error al enviar información \n" .
            "   Clase: " . __CLASS__ . "\n" .
            "   Mensaje: " . $e->getMessage() . "\n" .
            "   Línea: " . $e->getLine() . "\n" .
            "   Trace:\n" . collect($e->getTrace())
            ->take(3)
            ->map(function ($trace, $i) {
                return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
            })
            ->implode("\n") . "\n");
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::channel('restaurant')->error("Error al eliminar mesa \n" .
            "   Clase: " . __CLASS__ . "\n" .
            "   Mensaje: " . $e->getMessage() . "\n" .
            "   Línea: " . $e->getLine() . "\n" .
            "   Trace:\n" . collect($e->getTrace())
            ->take(3)
            ->map(function ($trace, $i) {
                return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
            })
            ->implode("\n") . "\n");
            return response()->json([
                'error' =>  $e->getMessage()
            ], 500);
        }
    }

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException(
                $id <= 0 ? "El ID debe ser positivo" : "El ID debe ser numérico"
            );
        }
    }
}

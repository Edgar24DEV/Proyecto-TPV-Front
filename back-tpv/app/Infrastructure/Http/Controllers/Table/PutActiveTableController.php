<?php

namespace Controllers\Table;

use App\Application\Table\DTO\UpdateActiveTableCommand;
use App\Application\Table\Handlers\UpdateActiveTableCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Table\UseCases\UpdateActiveTableUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutActiveTableController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateActiveTableUseCase $updateActiveTableUseCase,
        private readonly UpdateActiveTableCommandHandler $updateActiveTableCommandHandler
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->validateOrFail($request);

            $command = new UpdateActiveTableCommand(
                (int) $request->get("id"),
                $request->get("mesa"),
                $request->get("activo"),
                $request->get("id_ubicacion"),
                $request->get("pos_x"),
                $request->get("pos_y")
            );

            $result = $this->updateActiveTableCommandHandler->handle($command);

            if ($result->getId() === -1) {
                return response()->json([
                    'error' => 'No se pudo actualizar la mesa.'
                ], 401);
            }

            $this->validateOfUseCase($result);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
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
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::channel('restaurant')->error("Error al activar  mesa {$command->getId()} \n" .
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
                'error' => 'Hubo un error al procesar la solicitud.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function validateOrFail(Request $request): void
    {
        $request->validate([
            "id" => "required|integer",
            "mesa" => "required|string|max:255",
            "activo" => "required|in:0,1",
            "id_ubicacion" => "required|integer|exists:ubicaciones,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if (is_null($result) || $result->getId() < 1) {
            $this->apiError("No se ha podido actualizar la mesa.");
        }
    }
}

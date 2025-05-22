<?php

namespace Controllers\Table;

use App\Application\Table\DTO\AddTableCommand;
use App\Application\Table\Handlers\AddTableCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Table\UseCases\AddTableUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PostTableController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly AddTableUseCase $addTableUseCase,
        private readonly AddTableCommandHandler $addTableCommandHandler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->validateOrFail($request);

            $command = new AddTableCommand(
                ucfirst(strtolower($request->get("mesa"))),
                $request->get("id_ubicacion"),
                $request->get("pos_x"),
                $request->get("pos_y")
            );

            $result = $this->addTableCommandHandler->handle($command);
            $this->validateOfUseCase($result);

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('restaurant')->error("Error al añadir  mesa {$command->getMesa()} \n" .
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
        }
    }

    private function validateOrFail($request): void
    {
        $request->validate([
            "mesa" => "required|string|max:255",
            "id_ubicacion" => "required|integer|exists:ubicaciones,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear la mesa");
        }
    }
}

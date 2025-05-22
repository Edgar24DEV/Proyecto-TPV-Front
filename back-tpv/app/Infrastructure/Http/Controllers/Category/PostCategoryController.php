<?php

namespace Controllers\Category;

use App\Application\Category\DTO\AddCategoryCommand;
use App\Application\Category\Handlers\AddCategoryCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Category\UseCases\AddCategoryUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PostCategoryController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddCategoryUseCase $addCategoryUseCase,
        private readonly AddCategoryCommandHandler $addCategoryCommandHandler,
    ) {}


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->validateOrFail($request);
            $command = new AddCategoryCommand(
                ucfirst(strtolower($request->get("categoria"))),
                $request->get("id_empresa"),
            );

            $result = $this->addCategoryCommandHandler->handle($command);
            $this->validateOfUseCase($result);
            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('product')->warning(
                "ID inválido para añadir  categoría: {$request->input('id')} \n" .
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
            return response()->json([
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        }
    }

    private function validateOrFail($request): void
    {
        $request->validate([
            "categoria" => "required|string|max:255",
            "id_empresa" => "required|integer|exists:empresas,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear la categoria");
        }
    }
}

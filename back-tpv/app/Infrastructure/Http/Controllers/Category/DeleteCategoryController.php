<?php

namespace Controllers\Category;

use App\Application\Category\DTO\DeleteCategoryCommand;
use App\Application\Category\Handlers\DeleteCategoryCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Category\UseCases\DeleteCategoryUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class DeleteCategoryController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteCategoryUseCase $deleteCategoryUseCase,
        private readonly DeleteCategoryCommandHandler $deleteCategoryCommandHandler,
    ) {}


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->isNanId($request->input("id"));
            $id = new DeleteCategoryCommand($request->input("id"));
            $result = $this->deleteCategoryCommandHandler->handle($id);

            if (!$result) {
                // También podrías loguear aquí si quieres
                Log::channel('product')->warning("No se pudo borrar la categoría con ID: {$id->getId()}\n", [
                    '\n     class' => __CLASS__,
                    '\n\t line' => __LINE__,
                ]);

                return response()->json([
                    'error' => "Error al intentar borrar"
                ], 404);
            }

            return response()->json("Borrado con éxito");
        } catch (\InvalidArgumentException $e) {
            Log::channel('product')->warning(
                "ID inválido para eliminación de categoría: {$request->input('id')} \n" .
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
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::channel('product')->error(
                "Error eliminando categoría {$request->input('id')}\n" .
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
                'error' => $e->getMessage()
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

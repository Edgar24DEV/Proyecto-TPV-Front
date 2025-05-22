<?php

namespace Controllers\Restaurant;

use App\Application\Restaurant\DTO\DeleteRestaurantCommand;
use App\Application\Restaurant\Handlers\DeleteRestaurantCommandHandler;
use App\Application\Restaurant\UseCases\DeleteRestaurantUseCase;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class DeleteRestaurantController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteRestaurantUseCase $deleteRestaurantUseCase,
        private readonly DeleteRestaurantCommandHandler $deleteRestaurantCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {

        $id = new DeleteRestaurantCommand(
            $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $success = $this->deleteRestaurantCommandHandler->handle($id);

            if ($success) {
                return response()->json("Restaurante eliminado con éxito");
            }

            return response()->json([
                'error' => "No se pudo eliminar el Restaurante"
            ], 500);

        } catch (\InvalidArgumentException $e) {
            Log::channel('restaurant')->warning(
                "No se pudo borrar el restaurante \n" .
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
            Log::channel('restaurant')->error(
                "No se pudo borrar el restaurante \n" .
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
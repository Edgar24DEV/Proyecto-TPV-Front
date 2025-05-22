<?php

namespace Controllers\Location;

use App\Application\Location\DTO\DeleteLocationCommand;
use App\Application\Location\Handlers\DeleteLocationCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Location\UseCases\DeleteLocationUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class DeleteLocationController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteLocationUseCase $deleteLocationUseCase,
        private readonly DeleteLocationCommandHandler $deleteLocationCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        $id = new DeleteLocationCommand(
            $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $result = $this->deleteLocationCommandHandler->handle($id);
            if(!$result){
                return response()->json([
                    'error' =>  "Error al intentar borrar"
                ], 404);
            }
            return response()->json("Borrado con exito");
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('restaurant')->warning(
                "La validación falló al borrar la ubicación {$request->input('id')}\n" .
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
        }catch (\InvalidArgumentException $e) {
            Log::channel('restaurant')->warning(
                "Error en algún campo al borrar la ubicación {$request->input('id')}\n" .
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
        catch (\Exception $e) {
            Log::channel('restaurant')->error(
                "Error al borrar la ubicación {$request->input('id')}\n"  .
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
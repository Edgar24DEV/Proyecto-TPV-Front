<?php

namespace Controllers\Location;

use App\Application\Location\DTO\AddLocationCommand;
use App\Application\Location\Handlers\AddLocationCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Location\UseCases\AddLocationUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class PostLocationController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddLocationUseCase $addLocationUseCase,
        private readonly AddLocationCommandHandler $addLocationCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->validateOrFail($request);
            $command = new AddLocationCommand(
                ucfirst(strtolower($request->get("ubicacion"))),
                $request->get("id_restaurante"),
            );

            $result = $this->addLocationCommandHandler->handle($command);
            $this->validateOfUseCase($result);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('restaurant')->warning(
                "La validación falló al crear la ubicación {$request->input('ubicacion')} del restaurante {$request->input('id_restaurnate')}\n" .
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
                "Error en algún campo al crear la ubicación {$request->input('ubicacion')} del restaurante {$request->input('id_restaurnate')}\n" .
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
                "Error al crear la ubicación {$request->input('ubicacion')} del restaurante {$request->input('id_restaurnate')}\n"  .
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

    private function validateOrFail($request): void
    {
        $request->validate([
            "ubicacion" => "required|string|max:255",
            "id_restaurante" => "required|integer|exists:restaurantes,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear la ubicación");
        }
    }
}
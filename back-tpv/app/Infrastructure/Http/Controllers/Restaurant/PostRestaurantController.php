<?php

namespace Controllers\Restaurant;

use App\Application\Restaurant\DTO\AddRestaurantCommand;
use App\Application\Restaurant\Handlers\AddRestaurantCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Restaurant\UseCases\AddRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class PostRestaurantController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddRestaurantUseCase $addRestaurantUseCase,
        private readonly AddRestaurantCommandHandler $addRestaurantCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->validateOrFail($request);
            $command = new AddRestaurantCommand(
                $request->get("nombre"),
                $request->get("direccion"),
                $request->get("telefono"),
                $request->get("contrasenya"),
                $request->get("direccion_fiscal"),
                $request->get("cif"),
                $request->get("razon_social"),
                $request->get("id_empresa"),
            );

            $result = $this->addRestaurantCommandHandler->handle($command);
            $this->validateOfUseCase($result);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('restaurant')->error(
                "No se pudo encontrar el restaurante \n" .
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
            "nombre" => "required|string|max:100",
            "direccion" => "required|string|max:255",
            "telefono" => "required|string|regex:/^[0-9\s\+\-]{7,20}$/",
            "contrasenya" => "required|string|min:6|max:50|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]+$/",
            "direccion_fiscal" => "required|string|max:255",
            "cif" => "required|string|regex:/^[A-Za-z0-9]{8,12}$/",
            "razon_social" => "required|string|max:150",
            "id_empresa" => "required|integer|exists:empresas,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear el restaurante");
        }
    }
}
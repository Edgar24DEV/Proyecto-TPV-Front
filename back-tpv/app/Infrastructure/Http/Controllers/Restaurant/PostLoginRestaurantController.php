<?php

namespace Controllers\Restaurant;

use App\Application\Restaurant\DTO\LoginRestaurantCommand;
use App\Application\Restaurant\Handlers\LoginRestaurantCommandHandler;
use App\Domain\Restaurant\Entities\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Restaurant\UseCases\LoginRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class PostLoginRestaurantController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly LoginRestaurantUseCase $loginRestaurantsUseCase,
        private readonly LoginRestaurantCommandHandler $loginRestaurantCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {

        try {

            $this->validateOrFail($request);

            $command = new LoginRestaurantCommand(
                $request->input("nombre"),
                $request->input("contrasenya"),
            );

            $result = $this->loginRestaurantCommandHandler->handle($command);

            if ($result->getId() === -1) {
                Log::channel('restaurant')->warning("No se pudo encontrar el restaurante\n", [
                    '\n     class' => __CLASS__,
                    '\n\t line' => __LINE__,
                ]);
                return response()->json([
                    'error' => 'No se pudo autenticar el restaurante. Verifica tus credenciales.'
                ], 401);
            }

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

    private function validateOrFail(Request $request): void
    {
        // Validamos los datos requeridos en el request
        $request->validate([
            "nombre" => "required|string|max:255",
            "contrasenya" => [
                "required",
                "string",
                "min:8",
                "regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/"
            ],
        ], [
            'contrasenya.regex' => 'La contraseña debe tener al menos una letra y un número.',
        ]);
    }


}
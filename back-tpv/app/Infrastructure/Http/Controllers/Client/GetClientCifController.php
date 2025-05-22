<?php

namespace Controllers\Client;

use App\Application\Client\DTO\FindClientCifCommand;
use App\Application\Client\Handlers\FindClientCifCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetClientCifController
{
    public function __construct(
        private readonly FindClientCifCommandHandler $commandHandler
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'cif' => 'required|string',
            ]);

            $coomand = new FindClientCifCommand(
                $request->input('cif'),
            );

            $client = $this->commandHandler->handle($coomand);

            return response()->json([
                'data' => $client
            ]);

        } catch (\Exception $e) {
            Log::channel('company')->warning(
                "No se pudo encontrar el cliente \n" .
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
            ], 400);
        }
    }
}
<?php 
// app/Http/Controllers/Pdf/ShowBillController.php
namespace Controllers\Pdf;

use App\Application\Order\DTO\ShowBillCommand;
use App\Application\Order\UseCases\ShowBillUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ShowBillController {
   
    public function __invoke(Request $request): JsonResponse {
        $invoiceNumber = $request->input('n_factura');
        $filename = "bill_{$invoiceNumber}.pdf";
        $path = "bills/{$filename}";
    
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        }
    
        // Para desarrollo local (http://localhost)
        $baseUrl = config('app.url'); // O usa 'http://localhost' directamente
        $absoluteUrl = $baseUrl . Storage::url($path);
        
        return response()->json([
            'url' => $absoluteUrl,
            // Ejemplo de respuesta: "url": "http://localhost/storage/bills/bill_4-25.pdf"
        ]);
    }
}
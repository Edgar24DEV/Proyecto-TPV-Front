<?php

namespace App\Infrastructure\Pdf;

use App\Domain\Order\Services\TicketGeneratorServiceInterface;
use App\Domain\Order\Entities\Order;
use App\Domain\Restaurant\Entities\Restaurant;
use Barryvdh\DomPDF\Facade\Pdf;

class DompdfTicketGeneratorService implements TicketGeneratorServiceInterface
{
    public function generate(Order $order, array $lines, float $total, float $iva, Restaurant $restaurant): string
    {
        // Lógica para generar el ticket y devolver la URL
        $pdf = Pdf::loadView('ticket', [
            'order' => $order,
            'lineas' => $lines,
            'total' => $total,
            'iva' => $iva,
            'restaurante' => $restaurant
        ]);

        // Establecer tamaño de página tipo ticket: 80mm x 200mm en puntos
        $pdf->setPaper([0, 0, 283.46, 566.93], 'portrait');

        // Crear el directorio si no existe
        $directory = storage_path('app/public/tickets');
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true); // Crear el directorio recursivamente
        }

        // Guardamos el PDF en el sistema de archivos
        $filename = 'ticket_' . $order->id . '.pdf';
        $path = $directory . '/' . $filename;
        $pdf->save($path);

        return asset("storage/tickets/{$filename}");
    }
}

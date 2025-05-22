<?php
namespace App\Domain\Order\Services;

use App\Domain\Order\Entities\Order;
use App\Domain\Restaurant\Entities\Restaurant;
use Illuminate\Queue\Console\RestartCommand;

class TicketGeneratorService implements TicketGeneratorServiceInterface
{
    public function generate(Order $order, array $lines, float $total, float $iva, Restaurant $restaurant): string
    {
        // Lógica para generar el ticket y devolver la URL
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ticket', [
            'order' => $order,
            'lineas' => $lines,
            'total' => $total,
            'iva' => $iva,
            'restaurante' => $restaurant
        ]);

        // Crear el directorio si no existe
        $directory = storage_path('app/public/tickets');
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true); // Crear el directorio recursivamente
        }

        // Guardamos el PDF en el sistema de archivos
        $filename = 'ticket_' . $order->id . '.pdf';
        $path = $directory . '/' . $filename;
        file_put_contents($path, $pdf->output());

        return url('storage/tickets/' . $filename);
    }
}

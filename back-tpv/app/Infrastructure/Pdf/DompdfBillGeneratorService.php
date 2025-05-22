<?php

namespace App\Infrastructure\Pdf;

use App\Domain\Company\Entities\Client;
use App\Domain\Order\Entities\Payment;
use App\Domain\Order\Services\BillGeneratorServiceInterface;
use App\Domain\Order\Services\TicketGeneratorServiceInterface;
use App\Domain\Order\Entities\Order;
use App\Domain\Restaurant\Entities\Restaurant;
use Barryvdh\DomPDF\Facade\Pdf;

class DompdfBillGeneratorService implements BillGeneratorServiceInterface
{
    public function generate(Order $order, array $lines, float $total, float $iva, Restaurant $restaurant, Client $client, Payment $payment): string
    {
        // Lógica para generar el ticket y devolver la URL
        $pdf = Pdf::loadView('bill', [
            'order' => $order,
            'lineas' => $lines,
            'total' => $total,
            'iva' => $iva,
            'restaurante' => $restaurant,
            'cliente' => $client,
            'pago' => $payment,
        ]);

        // Establecer tamaño de página tipo ticket: 80mm x 200mm en puntos
        $pdf->setPaper([0, 0, 283.46, 566.93], 'portrait');

        // Crear el directorio si no existe
        $directory = storage_path('app/public/bills');
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true); // Crear el directorio recursivamente
        }

        // Guardamos el PDF en el sistema de archivos
        $numFactura = str_replace('/', '-', $payment->nFactura);
        $filename = 'bill_' . $numFactura . '.pdf';
        $path = $directory . '/' . $filename;
        file_put_contents($path, $pdf->output());

        return url('storage/bills/' . $filename);
    }
}

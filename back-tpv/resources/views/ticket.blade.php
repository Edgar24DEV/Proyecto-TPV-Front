<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $order->id }}</title>
    <style>
        /* Reset completo de márgenes y padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            /* Ancho estándar para tickets */
            margin: 10px auto;
            padding: 15px 3px;
            /* Reducir padding lateral */
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
            margin-bottom: 10px;
        }

        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .restaurant-info {
            font-size: 10px;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            /* Reducido */
            font-size: 11px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            table-layout: fixed;
            /* Forzar ancho de columnas */
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            font-size: 11px;
        }

        .items-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 11px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .items-table .description {
            width: 45%;
            /* Ajustado para 4 columnas */
        }

        .items-table .quantity {
            text-align: center;
            width: 15%;
        }

        .items-table .unit-price {
            text-align: right;
            width: 20%;
            padding-right: 3px;
        }

        .items-table .total-price {
            text-align: right;
            width: 20%;
        }

        .total-section {
            border-top: 1px dashed #000;
            margin-top: 8px;
            padding-top: 8px;
            font-size: 11px;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .total-line.total {
            font-weight: bold;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 8px;
            line-height: 1.2;
        }

        .dividir {
            border-top: 1px dashed #000;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 11px;
        }

        .thank-you {
            margin-top: 10px;
            text-align: center;
            font-style: italic;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="restaurant-name">{{ $restaurante->nombre }}</div>
        <div class="restaurant-info">
            {{ $restaurante->direccion }}<br>
            {{ $restaurante->telefono }}
        </div>
        <div class="order-info">
            <span>Ticket: <strong>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></span>
            <span>{{ now()->format('d/m/Y H:i') }}</span>
        </div>
        <div class="order-info">
            <span>Mesa: {{ $order->idMesa }}</span>
            <span>Comensales: {{ $order->comensales }}</span>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="description">Descripción</th>
                <th class="quantity">Ud.</th>
                <th class="unit-price">P.Unit</th>
                <th class="total-price">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lineas as $line)
                <tr>
                    <td class="description">{{ $line->nombre }}</td>
                    <td class="quantity">{{ $line->cantidad }}</td>
                    <td class="unit-price">{{ number_format($line->precio, 2) }} €</td>
                    <td class="total-price">{{ number_format($line->precio * $line->cantidad, 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-line">
            <span>SUBTOTAL:</span>
            <span>{{ number_format($total - $iva, 2) }} €</span>
        </div>
        <div class="total-line">
            <span>IVA (10%):</span>
            <span>{{ number_format($iva, 2) }} €</span>
        </div>
        <div class="total-line total">
            <span>TOTAL:</span>
            <span>{{ number_format($total, 2) }} €</span>
        </div>
    </div>

    <div class="dividir">
        <div class="total-line">
            <span>Total por comensal:</span>
            <span>{{ number_format($total / $order->comensales, 2) }} €</span>
        </div>
    </div>

    <div class="footer">
        Gracias por su visita<br>
        {{ now()->format('d/m/Y H:i') }}<br>
        Ticket no válido como factura
    </div>

    <div class="thank-you">
        ¡Vuelva pronto!
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura {{ $pago->nFactura }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 10px auto;
            padding: 15px 3px;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
            margin-bottom: 10px;
        }

        .company-info,
        .client-info {
            font-size: 10px;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            table-layout: fixed;
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

        .description {
            width: 45%;
        }

        .quantity {
            text-align: center;
            width: 15%;
        }

        .unit-price {
            text-align: right;
            width: 20%;
            padding-right: 3px;
        }

        .total-price {
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
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <strong class="restaurant-name">{{ $restaurante->razonSocial }}</strong><br>
            CIF: {{ $restaurante->cif }}<br>
            {{ $restaurante->direccionFiscal }}<br>
            Tel: {{ $restaurante->telefono }}
        </div>

        <div class="client-info">
            <strong>Cliente:</strong><br>
            {{ $cliente->getRazonSocial() }}<br>
            CIF: {{ $cliente->getCif() }}<br>
            {{ $cliente->getDireccionFiscal() }}<br>
            {{ $cliente->getCorreo() }}<br>
        </div>

        <div class="invoice-info">
            <span>Factura: <strong>#{{ str_pad($pago->nFactura, 5, '0', STR_PAD_LEFT) }}</strong></span>
            <span>{{ now()->format('d/m/Y H:i') }}</span>
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

    <div class="footer">
        Gracias por su confianza<br>
        {{ now()->format('d/m/Y') }}<br>
        Esta factura ha sido generada automáticamente
    </div>
</body>

</html>
<?php

namespace App\Domain\Order\Entities;

class Payment
{
    public function __construct(
        public ?int $id=null,
        public string $tipo,
        public float $cantidad,
        public string $fecha,
        public int $idPedido,
        public ?int $idCliente = null,
        public ?string $razonSocial = null,
        public ?string $CIF = null,
        public ?string $nFactura = null,
        public ?string $correo = null,
        public ?string $direccionFiscal = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'cantidad' => $this->cantidad,
            'fecha' => $this->fecha,
            'id_pedido' => $this->idPedido,
            'id_cliente' => $this->idCliente,
            'razon_social' => $this->razonSocial,
            'cif' => $this->CIF,
            'n_factura' => $this->nFactura,
            'correo' => $this->correo,
            'direccion_fiscal' => $this->direccionFiscal,
        ];
    }
}

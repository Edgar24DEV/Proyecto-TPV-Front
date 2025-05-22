<?php

namespace App\Application\Payment\DTO;

class AddPaymentCommand
{
    public function __construct(
        private readonly ?string $tipo,
        private readonly ?float $cantidad,
        private readonly ?string $fecha,
        private readonly ?int $idPedido,
        private readonly ?int $idCliente = null,
        private readonly ?string $razonSocial = null,
        private readonly ?string $CIF = null,
        private readonly ?string $nFactura = null,
        private readonly ?string $correo = null,
        private readonly ?string $direccionFiscal = null,
    ) {
    }

    public function toArray(): array
    {
        return [
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


    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function getCantidad(): ?float
    {
        return $this->cantidad;
    }

    public function getFecha(): ?string
    {
        return $this->fecha;
    }

    public function getIdPedido(): ?int
    {
        return $this->idPedido;
    }

    public function getIdCliente(): ?int
    {
        return $this->idCliente;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function getCIF(): ?string
    {
        return $this->CIF;
    }

    public function getNFactura(): ?string
    {
        return $this->nFactura;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function getDireccionFiscal(): ?string
    {
        return $this->direccionFiscal;
    }
}

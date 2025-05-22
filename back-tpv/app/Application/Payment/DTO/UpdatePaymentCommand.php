<?php  

namespace App\Application\Payment\DTO;

class UpdatePaymentCommand
{
    public function __construct(
        private readonly ?int $idPago = null,
        private readonly ?int $idCliente = null,
        private readonly ?string $razonSocial = null,
        private readonly ?string $CIF = null,
        private readonly ?string $nFactura = null,
        private readonly ?string $correo = null,
        private readonly ?string $direccionFiscal = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id_pago' => $this->idPago,
            'id_cliente' => $this->idCliente,
            'razon_social' => $this->razonSocial,
            'cif' => $this->CIF,
            'n_factura' => $this->nFactura,
            'correo' => $this->correo,
            'direccion_fiscal' => $this->direccionFiscal,
        ];
    }

    public function getIdPago(): ?int
    {
        return $this->idPago;
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

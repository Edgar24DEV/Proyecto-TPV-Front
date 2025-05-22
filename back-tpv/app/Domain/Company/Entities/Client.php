<?php
namespace App\Domain\Company\Entities;

class Client
{
    public ?int $id;
    public ?string $razon_social;
    public ?string $cif;
    public string $direccion_fiscal;
    public ?string $correo;
    public ?int $id_empresa;

    // Constructor para inicializar la entidad
    public function __construct(
        ?int $id,
        ?string $razon_social,
        ?string $cif,
        string $direccion_fiscal,
        ?string $correo,
        ?int $id_empresa
    ) {
        $this->id = $id;
        $this->razon_social = $razon_social;
        $this->cif = $cif;
        $this->direccion_fiscal = $direccion_fiscal;
        $this->correo = $correo;
        $this->id_empresa = $id_empresa;
    }

    // Métodos getter para acceder a los atributos
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razon_social;
    }

    public function getCif(): ?string
    {
        return $this->cif;
    }

    public function getDireccionFiscal(): string
    {
        return $this->direccion_fiscal;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->id_empresa;
    }
}

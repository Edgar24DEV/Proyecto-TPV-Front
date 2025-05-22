<?php



namespace App\Domain\Order\Entities;

class OrderLine
{
    public int $id;
    public int $idPedido;
    public int $idProducto;
    public int $cantidad;
    public float $precio;
    public string $nombre;
    public ?string $observaciones;
    public string $estado;

    // Constructor corregido
    public function __construct(
        int $id,
        int $idPedido,
        int $idProducto,
        int $cantidad,
        float $precio,
        string $nombre,
        ?string $observaciones,
        string $estado
    ) {
        $this->id = $id;
        $this->idPedido = $idPedido;
        $this->idProducto = $idProducto;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->nombre = $nombre;
        $this->observaciones = $observaciones;
        $this->estado = $estado;
    }
}


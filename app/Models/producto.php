<?php

class producto
{
 private ?int $idProducto;
 private string $nombre;
 private string $descripcion;
 private int $valorUnitario;
 private string  $estado;
 private int $stock;

    /**
     * @param int|null $idProducto
     * @param string $nombre
     * @param string $descripcion
     * @param int $valorUnitario
     * @param string $estado
     * @param int $stock
     */
    public function __construct(?int $idProducto, string $nombre, string $descripcion, int $valorUnitario, string $estado, int $stock)
    {
        $this->idProducto = $idProducto;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->valorUnitario = $valorUnitario;
        $this->estado = $estado;
        $this->stock = $stock;
    }

    /**
     * @return int|null
     */
    public function getIdProducto(): ?int
    {
        return $this->idProducto;
    }

    /**
     * @param int|null $idProducto
     */
    public function setIdProducto(?int $idProducto): void
    {
        $this->idProducto = $idProducto;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return int
     */
    public function getValorUnitario(): int
    {
        return $this->valorUnitario;
    }

    /**
     * @param int $valorUnitario
     */
    public function setValorUnitario(int $valorUnitario): void
    {
        $this->valorUnitario = $valorUnitario;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }


}

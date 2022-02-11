<?php

class venta
{
    private ?int $idVenta;
    private string $numeroVenta;
    private Carbon $fecha;
    private int $total;
    private string $costo_domicilio;
    private string $estado;
    private int $Domicilio_id_Domicilio;
    private int $Usuario_id_Usuario;

    /**
     * @param int|null $idVenta
     * @param string $numeroVenta
     * @param Carbon $fecha
     * @param int $total
     * @param string $costo_domicilio
     * @param string $estado
     * @param int $Domicilio_id_Domicilio
     * @param int $Usuario_id_Usuario
     */
    public function __construct(?int $idVenta, string $numeroVenta, Carbon $fecha, int $total, string $costo_domicilio, string $estado, int $Domicilio_id_Domicilio, int $Usuario_id_Usuario)
    {
        $this->idVenta = $idVenta;
        $this->numeroVenta = $numeroVenta;
        $this->fecha = $fecha;
        $this->total = $total;
        $this->costo_domicilio = $costo_domicilio;
        $this->estado = $estado;
        $this->Domicilio_id_Domicilio = $Domicilio_id_Domicilio;
        $this->Usuario_id_Usuario = $Usuario_id_Usuario;
    }

    /**
     * @return int|null
     */
    public function getIdVenta(): ?int
    {
        return $this->idVenta;
    }

    /**
     * @param int|null $idVenta
     */
    public function setIdVenta(?int $idVenta): void
    {
        $this->idVenta = $idVenta;
    }

    /**
     * @return string
     */
    public function getNumeroVenta(): string
    {
        return $this->numeroVenta;
    }

    /**
     * @param string $numeroVenta
     */
    public function setNumeroVenta(string $numeroVenta): void
    {
        $this->numeroVenta = $numeroVenta;
    }

    /**
     * @return Carbon
     */
    public function getFecha(): Carbon
    {
        return $this->fecha;
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getCostoDomicilio(): string
    {
        return $this->costo_domicilio;
    }

    /**
     * @param string $costo_domicilio
     */
    public function setCostoDomicilio(string $costo_domicilio): void
    {
        $this->costo_domicilio = $costo_domicilio;
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
    public function getDomicilioIdDomicilio(): int
    {
        return $this->Domicilio_id_Domicilio;
    }

    /**
     * @param int $Domicilio_id_Domicilio
     */
    public function setDomicilioIdDomicilio(int $Domicilio_id_Domicilio): void
    {
        $this->Domicilio_id_Domicilio = $Domicilio_id_Domicilio;
    }

    /**
     * @return int
     */
    public function getUsuarioIdUsuario(): int
    {
        return $this->Usuario_id_Usuario;
    }

    /**
     * @param int $Usuario_id_Usuario
     */
    public function setUsuarioIdUsuario(int $Usuario_id_Usuario): void
    {
        $this->Usuario_id_Usuario = $Usuario_id_Usuario;
    }


}
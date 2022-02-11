<?php

class pago
{
  private int $idPago;
  private int $abono;
  private string $saldo;
  private Carbon $fechaPago;
  private string $descuento;
  private string $estado;
  private int $Venta_id_Venta;
  private int $Usuario_id_Usuario;

    /**
     * @param int $idPago
     * @param int $abono
     * @param string $saldo
     * @param Carbon $fechaPago
     * @param string $descuento
     * @param string $estado
     * @param int $Venta_id_Venta
     * @param int $Usuario_id_Usuario
     */
    public function __construct(int $idPago, int $abono, string $saldo, Carbon $fechaPago, string $descuento, string $estado, int $Venta_id_Venta, int $Usuario_id_Usuario)
    {
        $this->idPago = $idPago;
        $this->abono = $abono;
        $this->saldo = $saldo;
        $this->fechaPago = $fechaPago;
        $this->descuento = $descuento;
        $this->estado = $estado;
        $this->Venta_id_Venta = $Venta_id_Venta;
        $this->Usuario_id_Usuario = $Usuario_id_Usuario;
    }

    /**
     * @return int
     */
    public function getIdPago(): int
    {
        return $this->idPago;
    }

    /**
     * @param int $idPago
     */
    public function setIdPago(int $idPago): void
    {
        $this->idPago = $idPago;
    }

    /**
     * @return int
     */
    public function getAbono(): int
    {
        return $this->abono;
    }

    /**
     * @param int $abono
     */
    public function setAbono(int $abono): void
    {
        $this->abono = $abono;
    }

    /**
     * @return string
     */
    public function getSaldo(): string
    {
        return $this->saldo;
    }

    /**
     * @param string $saldo
     */
    public function setSaldo(string $saldo): void
    {
        $this->saldo = $saldo;
    }

    /**
     * @return Carbon
     */
    public function getFechaPago(): Carbon
    {
        return $this->fechaPago;
    }

    /**
     * @param Carbon $fechaPago
     */
    public function setFechaPago(Carbon $fechaPago): void
    {
        $this->fechaPago = $fechaPago;
    }

    /**
     * @return string
     */
    public function getDescuento(): string
    {
        return $this->descuento;
    }

    /**
     * @param string $descuento
     */
    public function setDescuento(string $descuento): void
    {
        $this->descuento = $descuento;
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
    public function getVentaIdVenta(): int
    {
        return $this->Venta_id_Venta;
    }

    /**
     * @param int $Venta_id_Venta
     */
    public function setVentaIdVenta(int $Venta_id_Venta): void
    {
        $this->Venta_id_Venta = $Venta_id_Venta;
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
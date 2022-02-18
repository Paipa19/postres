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


    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':idPago' =>   $this->getIdPago() ];
        }else{
            $arrData = [
                ':idPago' =>   $this->getIdPago(),
                ':abono' =>   $this->getAbono(),
                ':saldo' =>  $this->getSaldo(),
                ':fechaPago' =>$this ->getFechaPago(),
                ':descuento'=> $this-> getDescuento(),
                ':estado' => $this->getEstado(),
                'Venta_id_Venta'=>$this->getVentaIdVenta(),
                'Usuario_id_Usuario' => $this ->Usuario_id_Usuario(),


            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;

    }

    function insert(): ?bool
    {
        $query = "INSERT INTO postres.pago VALUES (:idPago,:abono,:saldo,:fechaPago,:descuento,:estado,:Venta_id_Venta,:Usuario_id_Usuario)";
        if($this->save($query)){
            return $this->getProducto()->substractStock($this->getCantidad());
        }
        return false;
    }


    /**
     * @return mixed
     */
    public function update() : bool
    {
        $query = "UPDATE postres.pago SET 
            abono = :abono, saldo = :saldo, fechaPago = :fechaPago,descuento= :descuento
              WHERE idPago = :idpago";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM pago WHERE idpago = :idPago";
        return $this->save($query, 'deleted');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrPago = array();
            $tmp = new pago();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $pago = new ($valor);
                array_push($arrPago, $pago);
                unset($pago);
            }
            return $arrPago;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $idPago
     * @return mixed
     */
    public static function searchForId($idPago) : ?pago
    {
        try {
            if ($idPago > 0) {
                $pago= new pago();
                $pago->Connect();
                $getrow = $pago->getRow("SELECT * FROM weber.pago WHERE idPago = ?", array($idPago));
                $pago->Disconnect();
                return ($getrow) ? new pago($getrow) : null;
            }else{
                throw new Exception('Id de pago Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return pago::search("SELECT * FROM weber.pago");
    }

    /**
     * @param $idVenta
     * @param $idpPago
     * @return bool
     */

    public static function productoEnFactura($Venta_id_venta,$Usuario_id_Usuario): bool

    {
        $result = pago::search("SELECT idPago FROM weber.pago where idventa = '" . $Venta_id_venta. "' and $Usuario_id_Usuario = '" . $Usuario_id_Usuario. "'");
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Venta: ".$this->venta->getNumeroVenta().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, fechaVencimineto: $this->fechaVencimineto";
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        return [

            'idPago'=> $this->getIdPago(),
            'abono' => $this->getIdPago(),
            'saldo'=>$this->getSaldo(),
            'fechaPago' =>$this->getSaldo(),
            'descuento'=>$this->getDescuento(),
            'estado'=> $this->getEstado(),
             'Venta_id_Venta'=>$this->getVentaIdVenta(),
             'Usuario_id_Usuario'=>$this->getUsuarioIdUsuario(),

        ];
    }


}
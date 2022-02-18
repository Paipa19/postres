<?php

class detalleVenta
{
    private ?int $idDetalleVenta;
    private int $cantidad;
    private Carbon $fechaVencimineto;
    private int $Venta_id_venta;
    private int $Producto_id_Producto;

    /**
     * @param int|null $idDetalleVenta
     * @param int $cantidad
     * @param Carbon $fechaVencimineto
     * @param int $Venta_id_venta
     * @param int $Producto_id_Producto
     */
    public function __construct(?int $idDetalleVenta, int $cantidad, Carbon $fechaVencimineto, int $Venta_id_venta, int $Producto_id_Producto)
    {
        $this->idDetalleVenta = $idDetalleVenta;
        $this->cantidad = $cantidad;
        $this->fechaVencimineto = $fechaVencimineto;
        $this->Venta_id_venta = $Venta_id_venta;
        $this->Producto_id_Producto = $Producto_id_Producto;
    }

    /**
     * @return int|null
     */
    public function getIdDetalleVenta(): ?int
    {
        return $this->idDetalleVenta;
    }

    /**
     * @param int|null $idDetalleVenta
     */
    public function setIdDetalleVenta(?int $idDetalleVenta): void
    {
        $this->idDetalleVenta = $idDetalleVenta;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return Carbon
     */
    public function getFechaVencimineto(): Carbon
    {
        return $this->fechaVencimineto;
    }

    /**
     * @param Carbon $fechaVencimineto
     */
    public function setFechaVencimineto(Carbon $fechaVencimineto): void
    {
        $this->fechaVencimineto = $fechaVencimineto;
    }

    /**
     * @return int
     */
    public function getVentaIdVenta(): int
    {
        return $this->Venta_id_venta;
    }

    /**
     * @param int $Venta_id_venta
     */
    public function setVentaIdVenta(int $Venta_id_venta): void
    {
        $this->Venta_id_venta = $Venta_id_venta;
    }

    /**
     * @return int
     */
    public function getProductoIdProducto(): int
    {
        return $this->Producto_id_Producto;
    }

    /**
     * @param int $Producto_id_Producto
     */
    public function setProductoIdProducto(int $Producto_id_Producto): void
    {
        $this->Producto_id_Producto = $Producto_id_Producto;
    }


    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':idDetalleVenta' =>   $this->getIdDetalleVenta() ];
        }else{
            $arrData = [
                ':idDetalleVenta' =>   $this->getIdDetalleVenta(),
                ':cantidad' =>   $this->getCantidad(),
                ':fechaVencimineto' =>  $this->getFechaVencimineto(),
                'Venta_id_venta' =>$this ->getVentaIdVenta(),
                 'Producto_id_Producto'=> $this-> getProductoIdProducto(),
            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }


    function insert(): ?bool
    {
        $query = "INSERT INTO postres.detalleVenta VALUES (:idDetalleVenta,:cantidad,:fechaVencimineto,:Venta_id_venta,:Producto_id_Producto)";
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
        $query = "UPDATE postres.detalleVenta SET 
            Venta_id_Venta = :venta_id_Venta, Producto_id_Producto = :Producto_id_Producto, cantidad = :cantidad,fechaVencimiento= :fechaVencimiento 
              WHERE idDetalleVenta = :idDetalleVenta";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM detalleVenta WHERE idDetalleVenta = :idDetalleVenta";
        return $this->save($query, 'deleted');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrDetalleVenta = array();
            $tmp = new DetalleVenta();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetalleVenta = new DetalleVenta($valor);
                array_push($arrDetalleVenta, $DetalleVenta);
                unset($DetalleVenta);
            }
            return $arrDetalleVenta;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : ?DetalleVenta
    {
        try {
            if ($id > 0) {
                $DetalleVenta = new DetalleVenta();
                $DetalleVenta->Connect();
                $getrow = $DetalleVenta->getRow("SELECT * FROM weber.DetalleVenta WHERE idDetalleVenta = ?", array($id));
                $DetalleVenta->Disconnect();
                return ($getrow) ? new DetalleVenta($getrow) : null;
            }else{
                throw new Exception('Id de detalle venta Invalido');
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
        return DetalleVenta::search("SELECT * FROM weber.DetalleVenta");
    }

    /**
     * @param $venta_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($Venta_id_venta,$Producto_id_Producto): bool

    {
        $result = DetalleVenta::search("SELECT id FROM weber.DetalleVenta where Venta_id_venta = '" . $Venta_id_venta. "' and Producto_id_Producto = '" . $Producto_id_Producto. "'");
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
            'idDetalleVenta' => $this->getIdDetalleVenta(),
            'cantidad' => $this->getCantidad(),
            'fechaVencimineto' => $this->getVencimineto(),
            'Venta_id_Venta' => $this->getVentaIdVenta(),
            'Producto_id_Producto' => $this->getProductoIdProducto(),
        ];
    }

}
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

    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':idDetalleVenta' =>   $this->getIdDetalleVenta() ];
        }else{
            $arrData = [
                ':idVenta' =>   $this->getIdVenta(),
                ':numeroVenta' =>   $this->getNuemeroVenta(),
                ':fecha' =>  $this->getfecha(),
                ':total' =>$this ->getTotal(),
                ':costo_domicilio' => $this-> getCostoDomicilio(),
                ':estado' =>$this ->getEstado(),
                'Domicilio_id_Domicilio' =>$this ->getDomicilioIdDomicilio(),
                'Usuario_id_Usuario' =>$this ->getUsuarioIdUsuario(),

            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }


    function insert(): ?bool
    {
        $query = "INSERT INTO postres.venta VALUES (:idVenta,:numeroVenta,:fecha,:total,:costo_domicilio,:estado,:Domicilio_id_Domiicilio,:Usuario_id_Usuario)";
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
            idVenta = :idVenta, numeroVenta = :numeroVenta, fecha = :fecha,total= :total, costo_domicilio, :costo_domicilio, Domicilio_id_Domicilio, :Domicilio_id_Domicilio, Usuario_id_Usuario, :Usuario_id_Usuario; 
              WHERE idVenta = :idVenta";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM venta WHERE idVenta = :idVenta";
        return $this->save($query, 'deleted');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrventa = array();
            $tmp = new DetalleVenta();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $venta = new venta($valor);
                array_push($arrventa, $venta);
                unset($venta);
            }
            return $arrventa;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : ?venta
    {
        try {
            if ($id > 0) {
                $venta = new venta();
                $venta->Connect();
                $getrow = $venta->getRow("SELECT * FROM weber.venta WHERE id = ?", array($id));
                $venta->Disconnect();
                return ($getrow) ? new ventas($getrow) : null;
            }else{
                throw new Exception('Id de venta Invalido');
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
        return venta::search("SELECT * FROM weber.venta");
    }

    /**
     * @param $Domicilio_id_Domicilio
     * @param $Usuario_id_Usuario
     * @return bool
     */
    public static function productoEnFactura($Domicilio_id_Domicilio,$Usuario_id_Usuario): bool
    {
        $result = venta::search("SELECT id FROM weber.venta where idVenta = '" . $Domicilio_id_Domicilio. "' and Usuario_id_Usuario = '" . $Usuario_id_Usuario. "'");
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
        return "venta: ".$this->venta->getNumeroSerie().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Venta: $this->precio_venta";
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
            'idVenta' => $this->getIdVenta(),
            'numeroVenta' => $this->getNumeroVenta(),
            'fecha' => $this->getFecha(),
            'total' => $this->getTotal(),
            'costo_domicilio' => $this->getCostoDomicilio(),
            'estado' => $this->getEstado(),
            'Domicilio_id_Domicilio'=> $this->getDomicilioIdDomicilio(),
            'Usuario_id_Usuario'  => $this->getUsuarioIdUsuario(),

        ];
    }



}
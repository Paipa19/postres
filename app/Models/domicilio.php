<?php

class domicilio
{
   private int $idDomicilio;
   private string $direccion;
   private string $telefono;
   private int $municipios_id;
   private int $Usuario_id_Usuario;

    /**
     * @param int $idDomicilio
     * @param string $direccion
     * @param string $telefono
     * @param int $municipios_id
     * @param int $Usuario_id_Usuario
     */
    public function __construct(int $idDomicilio, string $direccion, string $telefono, int $municipios_id, int $Usuario_id_Usuario)
    {
        $this->idDomicilio = $idDomicilio;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->municipios_id = $municipios_id;
        $this->Usuario_id_Usuario = $Usuario_id_Usuario;
    }

    /**
     * @return int
     */
    public function getIdDomicilio(): int
    {
        return $this->idDomicilio;
    }

    /**
     * @param int $idDomicilio
     */
    public function setIdDomicilio(int $idDomicilio): void
    {
        $this->idDomicilio = $idDomicilio;
    }

    /**
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return int
     */
    public function getMunicipiosId(): int
    {
        return $this->municipios_id;
    }

    /**
     * @param int $municipios_id
     */
    public function setMunicipiosId(int $municipios_id): void
    {
        $this->municipios_id = $municipios_id;
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
            $arrData = [ ':idDomicilio' =>   $this->getIdDomicilio() ];
        }else{
            $arrData = [
                ':idDomicilio' =>   $this->getIdDomicilio(),
                ':direccion' =>   $this->getDireccion(),
                ':telefono' =>  $this->getTelefono(),
                'municipio_id' =>$this ->getMunicipiosId(),
                '$Usuario_id_Usuario;'=> $this->Usuario_id_Usuario(),
            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }


    function insert(): ?bool
    {
        $query = "INSERT INTO postres.domicilio VALUES (:idDomicilio,:direccion,:telefono,:municipios_id,:Usuario_id_Usuario)";
        if($this->save($query)){
            return $this->getUsuario()->substractStock($this->getdireccion());
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $query = "UPDATE postres.domicilio SET 
            municipios_id = :municipios_id, Usuario_id_Usuario = :Usuario_id_Usuario, direccion = :direccion,telefono= :telefono
              WHERE idDomicilio = :idDomicilio";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM domicilio WHERE idDomicilio = :idDomicilio";
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
            $tmp = new domicilio();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetalleVenta = new domicilio($valor);
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
    public static function searchForId($id) : ?domicilio
    {
        try {
            if ($id > 0) {
                $DetalleVenta = new domicilio();
                $DetalleVenta->Connect();
                $getrow = $DetalleVenta->getRow("SELECT * FROM weber.domicilio WHERE id = ?", array($id));
                $DetalleVenta->Disconnect();
                return ($getrow) ? new domicilio($getrow) : null;
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
        return domicilio::search("SELECT * FROM weber.domicilio");
    }

    /**
     * @param $municipios_id
     * @param $Usuario_id_usaurio
     * @return bool
     */
    public static function productoEnFactura($municipios_id,$Usuario_id_usuario): bool
    {
        $result = domicilio::search("SELECT idDomicilio FROM weber.domicilio where municipios_id = '" . $municipios_id. "' and Usuario_id_usaurio = '" . $Usuario_id_Usuario. "'");
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
        return "Venta: ".$this->venta->getNumeroVenta().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Vent: $this->precio_venta";
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
            'idDomicilio' => $this->getDomiclio(),
            'direccion' => $this->getDireccion(),
            'telefono' => $this->gettelefono(),
            'municipio_id' => $this->getMunicipiosId(),
            'Usuario_id_Usuario'=> $this->Usuario_id_Usuario(),

        ];
    }


}
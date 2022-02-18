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

    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':idDetalleVenta' =>   $this->getIdDetalleVenta() ];
        }else{
            $arrData = [
                ':idProducto' =>   $this->getIdProducto(),
                ':nombre' =>   $this->getNombre(),
                ':descripcion' =>  $this->getDescripcion(),
                ':valorUnitario' =>$this ->getValorUnitario(),
                ':estado'=> $this-> getEstado(),
                ':stock' => $this -> getStock(),
            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }
    function insert(): ?bool
    {
        $query = "INSERT INTO postres.producto VALUES (:idProducto,:nombre,:descripcion,:valorUnitario,:estado,:stock)";
        if($this->save($query)){
            return $this->getProducto()->substractStock($this->getCantidad());
        }
        return false;
    }
    public function update() : bool
    {
        $query = "UPDATE postres.producto SET 
            idProducto = :idProducto, nombre = :nombre, descripcion = :descripcion,valorUnitario= :valorUnitario,estado= :estado ,stock= :stock
              WHERE idproducto = :idproducto";
        return $this->save($query);
    }
    public function deleted() : bool
    {
        $query = "DELETE FROM producto WHERE id = :id";
        return $this->save($query, 'deleted');
    }

    public static function search($query) : ?array
    {
        try {
            $arrproducto = array();
            $tmp = new producto();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $producto = new producto($valor);
                array_push($arrproducto, $producto);
                unset($producto);
            }
            return $arrproducto;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    public static function searchForId($id) : ?producto
    {
        try {
            if ($id > 0) {
                $producto = new producto();
                $producto->Connect();
                $getrow = $producto->getRow("SELECT * FROM weber.producto WHERE id = ?", array($id));
                $producto->Disconnect();
                return ($getrow) ? new producto($getrow) : null;
            }else{
                throw new Exception('Id de producto Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }


    public static function getAll() : array
    {
        return producto::search("SELECT * FROM weber.producto");
    }


    public static function productoEnFactura($venta_id,$producto_id): bool
    {
        $result = producto::search("SELECT id FROM weber.producto where venta_id = '" . $venta_id. "' and producto_id = '" . $producto_id. "'");
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function __toString() : string
    {
        return "Venta: ".$this->venta->getNumeroSerie().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Venta: $this->precio_venta";
    }


    public function jsonSerialize()
    {
        return [
            'idProducto' => $this->getIdProducto(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'valorUnitario' => $this->getValorUnitario(),
            'estado' => $this->getEstado(),
            'stock' => $this -> getStock(),
        ];
    }
}

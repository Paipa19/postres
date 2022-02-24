<?php

use App\Models\GeneralFunctions;

class DetalleVentas extends AbstractDBConnection implements Model
{
    private ?int $idDetalleVenta;
    private string $cantidad;
    private carbon $fechaVencimiento;
    private int $Venta_idVenta;
    private int $Producto_idProducto;

    //Relaciones

    /**
     * @param int|null $idDetalleVenta
     * @param string $cantidad
     * @param carbon $fechaVencimiento
     */
    public function __construct(array $idDetalleVenta = [])

    {
        parent::__construct();
        $this->setIdDetalleVenta($$idDetalleVenta ['IdDetalleVenta'] ?? null);
        $this->setcantidad( $idDetalleVenta['cantidad'] ?? '');
        $this->setfechaVencimiento( $idDetalleVenta ['fechaVencimiento'] ?? '');
        $this->setVentaidVenta($idDetalleVenta ['Venta_idVenta'] ?? '');
        $this->setProductoidProducto($idDetalleVenta ['Producto_idProducto'] ?? '');

    }

    public function __destruct(){
        if ($this->isConnected()){
            $this->Disconnect();
        }
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
     * @return string
     */
    public function getCantidad(): string
    {
        return $this->cantidad;
    }

    /**
     * @param string $cantidad
     */
    public function setCantidad(string $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return carbon
     */
    public function getFechaVencimiento(): carbon
    {
        return $this->fechaVencimiento;
    }

    /**
     * @param carbon $fechaVencimiento
     */
    public function setFechaVencimiento(carbon $fechaVencimiento): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    /**
     * @return int
     */
    public function getVentaIdVenta(): int
    {
        return $this->Venta_idVenta;
    }

    /**
     * @param int $Venta_idVenta
     */
    public function setVentaIdVenta(int $Venta_idVenta): void
    {
        $this->Venta_idVenta = $Venta_idVenta;
    }

    /**
     * @return int
     */
    public function getProductoIdProducto(): int
    {
        return $this->Producto_idProducto;
    }

    /**
     * @param int $Producto_idProducto
     */
    public function setProductoIdProducto(int $Producto_idProducto): void
    {
        $this->Producto_idProducto = $Producto_idProducto;
    }






    protected function save(string $query): ?bool
    {

        $arrData = [
            ':idDetalleVenta' => $this->getIdDetalleVenta(),
            ':cantidad' => $this->getCantidad(),
            ':fechaVencimiento' => $this->getFechaVencimiento(),
            ':Venta_idVenta' => $this->getVentaIdVenta(),
            ':Producto_idProducto' => $this->getProductoIdProducto(),
        ];

        $this->Connet();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;

    }



    function insert(): ?bool
    {
        // TODO: Implement insert() method.

        $query = "INSERT INTO postres.detalleVenta SET
        :idDetalleVentas, :cantidad, :fechaVencimiento, :Venta_idVenta, :Producto_idProducto,)";

        return $this->save($query);


    }

    function update(): ?bool
    {
        // TODO: Implement update() method.

        $query = "UPDATE postres.detalleVenta SET
        cantidad = :cantidad, fechaVencimiento = :fechaVencimiento, Venta_idVenta = :Venta_idVenta, Producto_idProducto = :Producto_idProducto
        WHERE idDetalleVenta = :idDetalleVenta";
        return $this -> save($query);

    }



    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
        return null;
    }



    static function search($query): ?array
    {
        try {
            $arrDetalleVenta = array();
            $tmp = new DetalleVentas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetalleVenta = new DetalleVentas($valor);
                array_push($arrDetalleVenta, $DetalleVenta);
                unset($DetalleVenta);
            }
            return $arrDetalleVenta;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }



    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpDetalleVenta = new DetalleVentas();
                $tmpDetalleVenta->Connect();
                $getrow = $tmpDetalleVenta->getRow("SELECT * FROM postres.DetalleVentas WHERE idDetalleVentas =?", array($id));
                $tmpDetalleVenta->Disconnect();
                return ($getrow) ? new DetalleVentas($getrow) : null;
            } else {
                throw new Exception('Id de DetalleVentas Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }



    /**
     * @param $numeroDetalleVenta
     * @return bool
     */
    public static function DetalleVentaRegistrada($numeroDetalleVenta): bool
    {
        $result = DetalleVentas::search( "SELECT * FROM postres.DetalleVentas where numeroDetalleVenta = " . $numeroDetalleVenta);
        if (!empty($result) && count($result) > 0) {
            return true;
        } else {
            return false;
        }

    }
    static function getAll(): ?array
    {
        return DetalleVentas::search("SELECT * FROM postres.DetalleVentas");
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'idDetalleVenta' => $this->getIdDetalleVenta(),
            'cantidad' => $this->getCantidad(),
            'fechaVencimiento' => $this->getFechaVencimiento(),

        ];
    }
}
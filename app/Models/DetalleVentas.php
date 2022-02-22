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
    public function __construct(array $idDetalleVenta = [] )

    {
        parent::__construct();
        $this->setIdDetalleVenta($IdDetalleVenta ['IdDetalleVenta'] ?? null);
        $this->setcantidad( $cantidad['cantidad'] ?? '');
        $this->setfechaVencimiento( $fechaVencimiento ['fechaVencimiento'] ?? '');

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




    protected function save(string $query): ?bool
    {

        $arrData = [
            ':idDetalleVenta' => $this->getIdDetalleVenta(),
            ':cantidad' => $this->getCantidad(),
            ':fechaVencimiento' => $this->getFechaVencimiento(),
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
        :idDetalleVentas, :cantidad, :fechaVencimiento)";

        return $this->save($query);


    }

    function update(): ?bool
    {
        // TODO: Implement update() method.

        $query = "UPDATE postres.detalleVenta SET
        cantidad = :cantidad, fechaVencimiento = :fechaVencimiento";
        return $this - save($query);

    }



    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
        $this->setEstado(EstadoGeneral::INACTIVO); //cambia el estado de la venta
        return $this->update();
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



    public static function searchForId($id) : ?object
    {
        try {
            if ($id > 0) {
                $DetalleVenta = new DetalleVentas();
                $DetalleVenta->Connect();
                $getrow = $DetalleVenta->getRow("SELECT * FROM postres.DetalleVentas WHERE id = ?", array($id));
                $DetalleVenta->Disconnect();
                return ($getrow) ? new DetalleVentas($getrow) : null;
            }else{
                throw new Exception('Id de detalle venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    static function getAll(): ?array
    {
        return DetalleVentas::search( "SELECT * FROM postres.DetalleVentas");
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function DetalleVentaRegistrada($documento): bool
    {
        $result = DetalleVentas::search( "SELECT*FROM postres.detalleventas where documento = " . $documento);
        if (!empty($result) && count($result) > 0) {
            return true;
        } else {
            return false;
        }

    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "idDetalleVenta: $this->idDetalleVenta,
               cantidad: $this->cantidad,
               fechaVencimiento: $this->fechaVencimiento";
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'idDetalleVenta' => $this->getIdDetalleVenta(),
            'cantidad' => $this->getCantidad(),
            'fechaVencimiento' => $this->getFechaVencimiento(),

        ];
    }
}
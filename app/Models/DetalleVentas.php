<?php

namespace App\Models;


use App\Interfaces\Model;
use Carbon\Carbon;

class DetalleVentas  extends AbstractDBConnection implements Model
{
    private ?int $idDetalleVenta;
    private int $cantidad;
    private carbon $fechaVencimiento;
    private int $numDetalleVenta;
    private int $Venta_idVenta;
    private int $Producto_idProducto;

    //Relaciones

    /**
     * @param int|null $idDetalleVenta
     * @param string $cantidad
     * @param carbon $fechaVencimiento
     */
    public function __construct(array $DetalleVenta = [])

    {
        parent::__construct();
        $this->setIdDetalleVenta($DetalleVenta ['IdDetalleVenta'] ?? null);
        $this->setCantidad( $DetalleVenta['cantidad'] ?? 0);
        $this->setFechaVencimiento(!empty($DetalleVenta['fechaVencimiento'])?
            carbon::parse($DetalleVenta['fechaVencimiento']) : new carbon());
        $this->setNumDetalleVenta($DetalleVenta['numDetalleVenta']??0);
        $this->setVentaidVenta($DetalleVenta ['Venta_idVenta'] ?? 0);
        $this->setProductoidProducto($DetalleVenta ['Producto_idProducto'] ?? 0);

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
     * @return carbon
     */
    public function getFechaVencimiento(): carbon
    {
        return $this->fechaVencimiento->locale('es');
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
    public function getNumDetalleVenta(): int
    {
        return $this->numDetalleVenta;
    }

    /**
     * @param int $numDetalleVenta
     */
    public function setNumDetalleVenta(int $numDetalleVenta): void
    {
        $this->numDetalleVenta = $numDetalleVenta;
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
    public function setVentaidVenta(int $Venta_idVenta): void
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
            ':fechaVencimiento' => $this->getFechaVencimiento()->toDateString(),
            ':numDetalleVenta' =>$this->getIdDetalleVenta(),
            ':Venta_idVenta' => $this->getVentaIdVenta(),
            ':Producto_idProducto' => $this->getProductoIdProducto(),

        ];

        $this->Connect();
        var_dump($query, $arrData);
        die();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;

    }



    function insert(): ?bool
    {
        $query = "INSERT INTO postres.detalleventa SET(
        :idDetalleVenta, :cantidad, :fechaVencimiento,:numDetalleVenta, :Venta_idVenta, :Producto_idProducto) ";

        return $this->save($query);


    }

    function update(): ?bool
    {

        $query = "UPDATE postres.detalleventa SET
        cantidad = :cantidad, fechaVencimiento = :fechaVencimiento,numDetalleVenta =:numDetalleVenta, Venta_idVenta = :Venta_idVenta, Producto_idProducto = :Producto_idProducto
        WHERE idDetalleVenta = :idDetalleVenta";
        return $this -> save($query);

    }



    function deleted(): ?bool
    {
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



    static function searchForId(int $idDetalleVenta): ?object
    {
        try {
            if ($idDetalleVenta > 0) {
                $tmpDetalleVenta = new DetalleVentas();
                $tmpDetalleVenta->Connect();
                $getrow = $tmpDetalleVenta->getRow("SELECT * FROM postres.detalleventa WHERE idDetalleVentas =?", array($idDetalleVenta));
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

    static function getAll(): ?array
    {
        return DetalleVentas::search("SELECT * FROM postres.detalleventa");
    }

    /**
     * @param $numDetalleventa
     * @return bool
     * @throws Exception
     */

    public static function detalleVentaRegistrado ($cantidad): bool
    {
        $result = DetalleVentas::search("SELECT * FROM postres.detalleventa where cantidad ='" . $cantidad . "'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
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
            'numDetalleVenta' =>$this->getIdDetalleVenta(),
            'Venta_idVenta' => $this->getVentaIdVenta(),
            'Producto_idProducto' => $this->getProductoIdProducto(),

        ];
    }
}
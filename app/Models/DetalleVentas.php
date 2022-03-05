<?php

namespace App\Models;

require_once ("AbstractDBConnection.php");
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');

use App\Interfaces\Model;
use Carbon\Carbon;

class DetalleVentas  extends AbstractDBConnection implements Model
{
    private ?int $idDetalleVenta;
    private int $cantidad;
    private float $precioVenta;
    private int $Venta_idVenta;
    private int $Producto_idProducto;

  //RELACIONES
    private ?Ventas $Venta;
    private ?Productos $Producto;
    /**
     * @param int|null $idDetalleVenta
     * @param int $cantidad
     * @param carbon $fechaVencimiento
     */
    public function __construct(array $DetalleVenta = [])

    {
        parent::__construct();
        $this->setIdDetalleVenta($DetalleVenta ['idDetalleVenta'] ?? null);
        $this->setCantidad( $DetalleVenta['cantidad'] ?? 0);
        $this->setPrecioVenta($DetalleVenta ['precioVenta'] ?? 0);
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
     * @return float|mixed
     */
    public function getPrecioVenta() : float
    {
        return $this->precioVenta;
    }

    /**
     * @param float|mixed $precioVenta
     */
    public function setPrecioVenta(float $precioVenta): void
    {
        $this->precioVenta = $precioVenta;
    }

    public function getTotalProducto() : float
    {
        return $this->getPrecioVenta() * $this->getCantidad();
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


    /**
     * @return array|null
     */
    public function getVenta(): Ventas|null
    {
        if (!empty($this->Venta_idVenta)) {
            return Ventas::searchForId($this->Venta_idVenta) ?? new Ventas();
        }
        return null;
    }

    /**
     * Retorna el objeto producto correspondiente al detalle venta
     * @return Productos|null
     */
    public function getProducto(): ?Productos
    {
        if(!empty($this->Producto_idProducto)){
            $this->Producto = Productos::searchForId($this->Producto_idProducto) ?? new Productos();
            return $this->Producto;
        }
        return NULL;
    }


    /**
     * @param $venta_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($venta_id,$producto_id): bool
    {
        $result = DetalleVentas::search("SELECT idDetalleVenta FROM detalleventa where Venta_idVenta = '" . $venta_id. "' and Producto_idProducto = '" . $producto_id. "'");
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':idDetalleVenta' =>   $this->getIdDetalleVenta() ];
        }else {
            $arrData = [
                ':idDetalleVenta' => $this->getIdDetalleVenta(),
                ':cantidad' => $this->getCantidad(),
                ':precioVenta' => $this->getPrecioVenta(),
                ':Venta_idVenta' => $this->getVentaIdVenta(),
                ':Producto_idProducto' => $this->getProductoIdProducto(),

            ];
        }
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;

    }



    function insert(): ?bool
    {
        $query = "INSERT INTO postres.detalleventa values (
        :idDetalleVenta, :cantidad, :precioVenta,
         :Venta_idVenta, :Producto_idProducto)";
        if($this->save($query)){
            return $this->getProducto()->substractStock($this->getCantidad());
        }
        return false;


    }

    function update(): ?bool
    {

        $query = "UPDATE postres.detalleventa SET
        cantidad = :cantidad, precioVenta = :precioVenta, Venta_idVenta = :Venta_idVenta, Producto_idProducto = :Producto_idProducto
        WHERE idDetalleVenta = :idDetalleVenta";
        return $this -> save($query);

    }



    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM detalleventa WHERE idDetalleVenta = :idDetalleVenta";
        return $this->save($query, 'deleted');
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
                $getrow = $tmpDetalleVenta->getRow("SELECT * FROM postres.detalleventa WHERE idDetalleVenta =?", array($idDetalleVenta));
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
     * @param $cantidad
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
            'precioVenta' => $this->getPrecioVenta(),
            'Venta_idVenta' => $this->getVentaIdVenta(),
            'Producto_idProducto' => $this->getProductoIdProducto(),

        ];
    }
}
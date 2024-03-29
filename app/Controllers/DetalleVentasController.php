<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\DetalleVentas;
use Carbon\Carbon;

class DetalleVentasController
{
    private array $dataDetalleVenta;

    public function __construct(array $_FORM)
    {
        $this->dataDetalleVenta = array();
        $this->dataDetalleVenta['idDetalleVenta'] = $_FORM['idDetalleVenta'] ?? NULL;
        $this->dataDetalleVenta['cantidad'] = $_FORM['cantidad'] ?? '';
        $this->dataDetalleVenta['precioVenta'] = $_FORM['precioVenta'] ?? '';
        $this->dataDetalleVenta['Venta_idVenta'] = $_FORM['Venta_idVenta'] ?? '';
        $this->dataDetalleVenta['Producto_idProducto'] = $_FORM['Producto_idProducto'] ?? '';
    }

    public function create()
    {
        try {
            if (!empty($this->dataDetalleVenta['Venta_idVenta']) and !empty($this->dataDetalleVenta['Producto_idProducto'])) {
                if(DetalleVentas::productoEnFactura($this->dataDetalleVenta['Venta_idVenta'], $this->dataDetalleVenta['Producto_idProducto'])){
                    $this->edit();
                }else{
                    $DetalleVenta = new DetalleVentas($this->dataDetalleVenta);
                    if ($DetalleVenta->insert()) {
                        unset($_SESSION['frmDetalleVentas']);
                        header("Location: ../../views/modules/ventas/create.php?id=".$this->dataDetalleVenta['Venta_idVenta']."&respuesta=success&mensaje=Producto Agregado");
                    }
                }
            } else {
                header("Location: ../../views/modules/ventas/create.php?id=".$this->dataDetalleVenta['Venta_idVenta']."&respuesta=error&mensaje=Faltan parametros");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $arrDetalleVenta = DetalleVentas::search("SELECT * FROM detalleventa WHERE Venta_idVenta = ".$this->dataDetalleVenta['Venta_idVenta']." and Producto_idProducto = ".$this->dataDetalleVenta['Producto_idProducto']);
            /* @var $arrDetalleVenta DetalleVentas[] */
            $DetalleVenta = $arrDetalleVenta[0];
            $OldCantidad = $DetalleVenta->getCantidad();
            $DetalleVenta->setCantidad($OldCantidad + $this->dataDetalleVenta['cantidad']);
            if ($DetalleVenta->update()) {
                $DetalleVenta->getProducto()->substractStock($this->dataDetalleVenta['cantidad']);
                unset($_SESSION['frmDetalleVentas']);
                header("Location: ../../views/modules/ventas/create.php?id=".$this->dataDetalleVenta['Venta_idVenta']."&respuesta=success&mensaje=Producto Actualizado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function deleted (int $idDetalleVenta){
        try {
            $ObjDetalleVenta = DetalleVentas::searchForId($idDetalleVenta);
            $objProducto = $ObjDetalleVenta->getProducto();
            if($ObjDetalleVenta->deleted()){
                $objProducto->addStock($ObjDetalleVenta->getCantidad());
                header("Location: ../../views/modules/ventas/create.php?id=".$ObjDetalleVenta->getVentaIdVenta()."&respuesta=success&mensaje=Producto Eliminado");
            }else{
                header("Location: ../../views/modules/ventas/create.php?id=".$ObjDetalleVenta->getVentaIdVenta()."&respuesta=error&mensaje=Error al eliminar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = DetalleVentas::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function getAll()
    {
        try {
            $result = DetalleVentas::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }
}
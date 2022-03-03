<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Ventas;
use Carbon\Carbon;

class VentasController{

    private array $dataVenta;

    public function __construct(array $_FORM)
    {
        $this->dataVenta = array();
        $this->dataVenta['idVenta'] = $_FORM['idVenta'] ?? NULL;
        $this->dataVenta['numeroVenta'] = $_FORM['numeroVenta'] ?? '';
        $this->dataVenta['fecha'] = !empty($_FORM['fecha']) ? Carbon::parse($_FORM['fecha']) : new Carbon();
        $this->dataVenta['total'] = $_FORM['total'] ?? 0;
        $this->dataVenta['costoDomicilio'] = $_FORM['costoDomicilio'] ?? 0;
        $this->dataVenta['estado'] = $_FORM['estado'] ?? 'En progreso';
        $this->dataVenta['Usuario_idUsuario'] = $_FORM['Usuario_idUsuario'] ?? 0;
        $this->dataVenta['domicilio_idDomicilio'] = $_FORM['domicilio_idDomicilio'] ?? 0;



    }

    public function create() {
        try {
            $Venta = new Ventas($this->dataVenta);
            if ($Venta->insert()) {
                unset($_SESSION['frmVentas']);
                $Venta->Connect();
                $id = $Venta->getLastId('ventas');
                $Venta->Disconnect();
                header("Location: ../../views/modules/ventas/create.php?id=" . $id . "");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/ventas/create.php?respuesta=error");
        }
    }

    public function edit()
    {
        try {
            $Venta = new Ventas($this->dataVenta);
            if($Venta->update()){
                unset($_SESSION['frmVentas']);
            }
            header("Location: ../../views/modules/ventas/show.php?id=" . $Venta->getIdVenta() . "&respuesta=success&mensaje=Venta Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/ventas/edit.php?respuesta=error");
        }
    }

    static public function searchForID (array $data){
        try {
            $result = Ventas::searchForId($data['idVenta']);
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

    static public function getAll (array $data = null){
        try {
            $result = Ventas::getAll();
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

    static public function cancel(){
        try {
            $ObjVenta = Ventas::searchForId($_GET['Id']);
            $ObjVenta->setEstadoVenta("No_aprobada");
            if($ObjVenta->update()){
                header("Location: ../../views/modules/ventas/index.php");
            }else{
                header("Location: ../../views/modules/ventas/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/ventas/index.php?respuesta=error");
        }
    }

    static public function selectVentas (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "venta_id";
        $params['name'] = $params['name'] ?? "venta_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrVentas = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM venta WHERE ";
            $arrVentas = Ventas::search($base.$params['where']);
        }else{
            $arrVentas = Ventas::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrVentas) && count($arrVentas) > 0){
            /* @var $arrVentas Ventas[] */
            foreach ($arrVentas as $ventas)
                if (!VentasController::ventaIsInArray($ventas->getIdVenta(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($ventas != "") ? (($params['defaultValue'] == $ventas->getIdVenta()) ? "selected" : "" ) : "")." value='".$ventas->getIdVenta()."'>".$ventas->getNumeroVenta()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function ventaIsInArray($idVenta, $ArrVentas){
        if(count($ArrVentas) > 0){
            foreach ($ArrVentas as $Venta){
                if($Venta->getIdVenta() == $idVenta){
                    return true;
                }
            }
        }
        return false;
    }

}
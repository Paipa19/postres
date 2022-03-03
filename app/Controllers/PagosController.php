<?php
namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Pagos;
use Carbon\Carbon;

class PagosController{


    private array $dataPagos;

    public function __construct(array $_FORM)
    {
        $this->dataPagos = array();
        $this->dataPagos['idPago'] = $_FORM['idPago'] ?? NULL;
        $this->dataPagos['abono'] = $_FORM['abono'] ?? '';
        $this->dataPagos['saldo'] = $_FORM['saldo'] ?? 0;
        $this->dataPagos['fechaPago'] = !empty($_FORM['fechaPago']) ? Carbon::parse($_FORM['fechaPago']) : new Carbon();
        $this->dataPagos['descuento'] = $_FORM['descuento'] ?? 0;
        $this->dataPagos['estado'] = $_FORM['estado'] ?? 'Pendiente';
        $this->dataPagos['Venta_idVenta'] = $_FORM['Venta_idVenta'] ?? 0;
        $this->dataPagos['Usuario_idUsuario'] = $_FORM['Usuario_idUsuario'] ?? 0;

    }
    public function create($withFiles = null) {
        try {
            if (!empty($this->dataPagos['fechaPago']) && !Pagos::pagoRegistrado($this->dataPagos['fechaPago'])) {
                $Pago = new Pagos ($this->dataPagos);
                if ($Pago->insert()) {
                    unset($_SESSION['frmPagos']);
                    header("Location: ../../views/modules/pagos/index.php?respuesta=success&mensaje=Pago Registrado");
                }
            } else {
                header("Location: ../../views/modules/pagos/create.php?respuesta=error&mensaje=Pago ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        var_dump($this->dataPagos);
        try {
            $Pago = new Pagos($this->dataPagos);
            if($Pago->update()){
                unset($_SESSION['frmPagos']);
            }

            header("Location: ../../views/modules/pagos/show.php?id=" . $Pago->getIdPago() . "&respuesta=success&mensaje=Pago Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/ventas/edit.php?respuesta=error");
        }
    }
    static public function searchForId (array $data){
        try {
            $result = Pagos::searchForId($data['idPago']);
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
            $result = Pagos::getAll();
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
            $ObjPago = Pagos::searchForIdPago($_GET['IdPago']);
            $ObjPago->setEstadoPago("Cancelado");
            if($ObjPago->update()){
                header("Location: ../../views/modules/pagos/index.php");
            }else{
                header("Location: ../../views/modules/pagos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/pagos/index.php?respuesta=error");
        }
    }

    static public function selectPagos (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "Venta_idVenta";
        $params['name'] = $params['name'] ?? "Venta_idVenta";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrPagos = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM pagos WHERE ";
            $arrPagos = Pagos::search($base.$params['where']);
        }else{
            $arrPagos = Pagos::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrPagos) && count($arrPagos) > 0){
            /* @var $arrPagos ´Pagos[] */
            foreach ($arrPagos as $pagos)
                if (!PagosController::pagoIsInArray($pagos->getIdPago(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($pagos != "") ? (($params['defaultValue'] == $pagos->getIdPago()) ? "selected" : "" ) : "")." value='".$pagos->getIdPago()."'>".$pagos->getSaldo()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function pagoIsInArray($idPago, $ArrPagos){
        if(count($ArrPagos) > 0){
            foreach ($ArrPagos as $Pago){
                if($Pago->getIdVenta() == $idPago){
                    return true;
                }
            }
        }
        return false;
    }}
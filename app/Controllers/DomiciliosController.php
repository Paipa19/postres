<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Domicilios;

class DomiciliosController{

    private array $dataDomicilio;

    public function __construct(array $_FORM)
    {
        $this->dataDomicilio = array();
        $this->dataDomicilio['idDomicilio'] = $_FORM['idDomicilio'] ?? NULL;
        $this->dataDomicilio['direccion'] = $_FORM['direccion'] ?? '';
        $this->dataDomicilio['telefono'] = $_FORM['telefono'] ?? 0;
        $this->dataDomicilio['municipios_id'] = $_FORM['municipios_id'] ?? 0;
        $this->dataDomicilio['Usuario_idUsuario'] = $_FORM['Usuario_idUsuario'] ?? 0;
    }
    public function create() {
        try {
            if (!empty($this->dataDomicilio['direccion']) && !Domicilios::domicilioRegistrado($this->dataDomicilio['direccion'])) {
                $Domicilio = new Domicilios ($this->dataDomicilio);
                if ($Domicilio->insert()) {
                    unset($_SESSION['frmDomicilios']);
                    header("Location: ../../views/modules/domicilios/index.php?respuesta=success&mensaje=Domicilio Registrado!");
                }
            } else {
                header("Location: ../../views/modules/domicilios/create.php?respuesta=error&mensaje=Domicilio ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $domicilio = new Domicilios($this->dataDomicilio);

            if($domicilio->update()){
                unset($_SESSION['frmDomicilios']);
            }
            header("Location: ../../views/modules/domicilios/show.php?id=" . $domicilio->getIdDomicilio() . "&respuesta=success&mensaje=Domicilio Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }
    static public function searchForId (array $data)
    {
        try {
            $result = Domicilios::searchForId($data['idDomicilio']);
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
            $result = Domicilios::getAll();
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

    static public function selectDomicilios (array $params = [])
    {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "producto_id";
        $params['name'] = $params['name'] ?? "producto_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';


        $arrDomicilio = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM domicilio WHERE ";
            $arrDomicilio = Domicilios::search($base . $params['where']);
        } else {
            $arrDomicilio = Domicilios::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrDomicilio) && count($arrDomicilio) > 0) {
            /* @var $arrDomicilio Domicilios[] */
            foreach ($arrDomicilio as $domicilio)
                if (!DomiciliosController::domicilioIsInArray($domicilio->getIdDomicilio(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($domicilio != "") ? (($params['defaultValue'] == $domicilio->getIdDomicilio()) ? "selected" : "") : "") . " value='" . $domicilio->getIdDomicilio() . "'>" . $domicilio->getDireccion() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

        public static function domicilioIsInArray($idDomiclio, $ArrDomicilio)
    {
        if (count($ArrDomicilio) > 0) {
            foreach ($ArrDomicilio as $Domicilio) {
                if ($Domicilio->getIdDomiiclio() == $idDomiclio) {
                    return true;
                }
            }
        }

        return false;


    }
}
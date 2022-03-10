<?php

namespace App\Models;

use App\Interfaces\Model;

class Domicilios extends AbstractDBConnection implements Model
{
    private ?int $idDomicilio;
    private string $direccion;
    private string $telefono;
    private int $municipios_id;
    private int $Usuario_idUsuario;

    //RELACIONES
    private ?Municipios $Municipios;
    private ?Usuarios $Usuarios;

    /**
     * @param int|null $idDomicilio
     * @param string $direccion
     * @param string $telefono
     * @param int $municipios_id
     * @param int $Usuario_idUsuario
     * @param array|null $Municipios
     * @param array|null $Usuarios
     */
    public function __construct(array $domicilio= [])
    {
        parent::__construct();
        $this->setIdDomicilio($domicilio ['idDomicilio'] ?? null);
        $this->setDireccion($domicilio['direccion'] ?? '');
        $this->setTelefono($domicilio['telefono'] ?? '');
        $this->setMunicipiosId($domicilio['municipios_id'] ?? 0);
        $this->setUsuarioIdUsuario($domicilio['Usuario_idUsuario'] ?? 0);
    }


    function __destruct()
    {
        if($this->isConnected()){
            $this->Disconnect();
        }
    }



    /**
     * @return int|null
     */
    public function getIdDomicilio(): ?int
    {
        return $this->idDomicilio;
    }

    /**
     * @param int|null $idDomicilio
     */
    public function setIdDomicilio(?int $idDomicilio): void
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
        return $this->Usuario_idUsuario;
    }

    /**
     * @param int $Usuario_idUsuario
     */
    public function setUsuarioIdUsuario(int $Usuario_idUsuario): void
    {
        $this->Usuario_idUsuario = $Usuario_idUsuario;
    }

    /**
     * @return array|null
     */
    public function getMunicipio(): Municipios|null
    {
        if (!empty($this->municipios_id)) {
            return Municipios::searchForId($this->municipios_id) ?? new Municipios();
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getUsuario(): Usuarios|null
    {
        if (!empty($this->Usuario_idUsuario)) {
            return Usuarios::searchForId($this->Usuario_idUsuario) ?? new Usuarios();
        }
        return null;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idDomicilio' => $this->getIdDomicilio(),
            ':direccion' => $this->getDireccion(),
            ':telefono' => $this->getTelefono(),
            ':municipios_id' => $this->getMunicipiosId(),
            ':Usuario_idUsuario' => $this->getUsuarioIdUsuario()
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }



    function insert(): ?bool
    {
        $query = "INSERT INTO postres.domicilio VALUES (:idDomicilio, :direccion, :telefono, :municipios_id, :Usuario_idUsuario)";
        return $this->save($query);
    }

    function update(): ?bool
    {

        $query = "UPDATE postres.domicilio SET
           direccion = :direccion, telefono = :telefono,
           municipios_id = :municipios_id, Usuario_idUsuario = :Usuario_idUsuario WHERE idDomicilio = :idDomicilio";
        return $this ->save($query);

    }



    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
        return null;
    }


    static function search($query): ?array
    {
        try {
            $arrDomicilios = array();
            $tmp = new Domicilios();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Domicilios = new Domicilios ($valor);
                    array_push($arrDomicilios, $Domicilios);
                    unset($Domicilios);
                }
                return $arrDomicilios;
            }
            return null;
        } catch (Exception $e) {
            GeneralFuntions::logFile('Exeption', $e);
        }
        return null;
    }

    static function searchForId(int $idDomicilio): ?Domicilios
    {
        try {
            if ($idDomicilio > 0) {
                $Domicilio = new Domicilios();
                $Domicilio->Connect();
                $getrow = $Domicilio->getrow("SELECT * FROM postres.domicilio WHERE idDomicilio =?", array($idDomicilio));
                $Domicilio->Disconnect();
                return ($getrow) ? new Domicilios($getrow) : null;
            } else {
                throw new  Exception('Id de Domicilios invalida');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }


    static function getAll(): ?array
    {
        return Domicilios::search("SELECT * FROM postres.domicilio");
    }
    /**
     * @param $telefono
     * @return bool
     * @throws Exception
     */
    public static function domicilioRegistrado($direccion): bool
    {
        $result = Domicilios::search("SELECT * FROM postres.domicilio where direccion = '" . $direccion."'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }
    public function __toString(): string
    {
        return "direccion: $this->direccion, 
                telefono: $this->telefono, 
                municipios_id: $this->municipios_id,
                Usuario_idUsuario: $this->Usuario_idUsuario";
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'idDomicilio' => $this->getIdDomicilio(),
            'direccion' => $this->getDireccion(),
            'telefono' => $this->getTelefono(),
            'municipios_id' => $this->getMunicipiosId(),
            'Usuario_idUsuario' => $this->getUsuarioIdUsuario(),

        ];
    }
}
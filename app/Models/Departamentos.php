<?php

namespace App\Models;



use App\Enums\Estado;
use App\Enums\RegionDepartamento;
use App\Controllers\DepartamentosController;
use App\Interfaces\Model;
use Carbon\Carbon;


final class Departamentos extends AbstractDBConnection implements Model
{
    private ?int $id;
    private string $nombre;
    private RegionDepartamento $region;
    private Estado $estado;
    private Carbon $created_at;
    private Carbon $updated_at;
    private Carbon $deleted_at;

    /* Relaciones */
    private ?array $MunicipiosDepartamento;

    /**
     * Departamentos constructor. Recibe un array asociativo
     * @param array $departamento
     */
    public function __construct(array $departamento = [])
    {
        parent::__construct();
        $this->setId($departamento['id'] ?? null);
        $this->setNombre($departamento['nombre'] ?? '');
        $this->setRegion($departamento['region'] ?? RegionDepartamento::CENTRO_SUR);
        $this->setEstado($departamento['estado'] ?? Estado::ACTIVO);
        $this->setCreatedAt(!empty($departamento['created_at']) ?
            Carbon::parse($departamento['created_at']) : new Carbon());
        $this->setUpdatedAt(!empty($departamento['updated_at']) ?
            Carbon::parse($departamento['updated_at']) : new Carbon());
        $this->setDeletedAt(!empty($departamento['deleted_at']) ?
            Carbon::parse($departamento['deleted_at']) : new Carbon());
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
    public function getRegion(): string
    {
        return $this->region->toString();
    }

    /**
     * @param string|RegionDepartamento|null $region
     */
    public function setRegion(null|string|RegionDepartamento $region): void
    {
        if (is_string($region)){
            $this->region = RegionDepartamento::from($region);
        }else{
            $this->region = $region;
        }
    }

    /**
     * @return Estado
     */
    public function getEstado(): string
    {
        return ucwords($this->estado->toString());
    }

    /**
     * @param string|Estado|null $estado
     */
    public function setEstado(null|string|Estado $estado): void
    {
        if (is_string($estado)){
            $this->estado = estado::from($estado);
        }else{
            $this->estado = $estado;
        }
    }



    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at->locale('es');
    }

    /**
     * @param Carbon $created_at
     */
    public function setCreatedAt(Carbon $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at->locale('es');
    }

    /**
     * @param Carbon $updated_at
     */
    public function setUpdatedAt(Carbon $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return Carbon
     */
    public function getDeletedAt(): Carbon
    {
        return $this->deleted_at->locale('es');
    }

    /**
     * @param Carbon $deleted_at
     */
    public function setDeletedAt(Carbon $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }

    /* Relaciones */
    /**
     * retorna un array de municipios que perteneces a un departamento
     * @return array
     */
    public function getMunicipiosDepartamento(): ?array
    {
        $this-> MunicipiosDepartamento = Municipios::search(
            "SELECT * FROM municipios WHERE departamento_id = ".$this->id
        );
        return $this-> MunicipiosDepartamento ?? null;
    }

    public static function search($query): ?array
    {
        try {
            $arrDepartamentos = array();
            $tmp = new Departamentos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Departamento = new Departamentos($valor);
                    array_push($arrDepartamentos, $Departamento);
                    unset($Departamento);
                }
                return $arrDepartamentos;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    public static function searchForId(int $id): ?Departamentos
    {
        try {
            if ($id > 0) {
                $tmpDepartamento = new Departamentos();
                $tmpDepartamento->Connect();
                $getrow = $tmpDepartamento->getRow("SELECT * FROM departamentos WHERE id =?", array($id));
                $tmpDepartamento->Disconnect();
                return ($getrow) ? new Departamentos($getrow) : null;
            } else {
                throw new Exception('Id de departamento Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    public static function getAll(): array
    {
        return Departamentos::search("SELECT * FROM departamentos");
    }

    /**
     * @param $nombre
     * @throws Exception
     */

    public static function departamentoRegistrado($nombre): bool
    {
        $result = Departamentos::search("SELECT * FROM postres.departamentos where nombre = '" . $nombre . "'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }


    public function __toString() : string
    {
        return "Nombre: $this->nombre, MunicipiosDepartamento: $this->MunicipiosDepartamento, Estado: $this->estado";
    }





    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize() : array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'region' => $this->getRegion(),
            'estado' => $this->getEstado(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
            'deleted_at' => $this->getDeletedAt()->toDateTimeString(),
        ];
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':nombre' =>   $this->getNombre(),
            ':region' =>   $this->getRegion(),
            ':estado' =>   $this->getEstado(),
            ':created_at' =>  $this->getCreatedAt()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':updated_at' =>  $this->getUpdatedAt()->toDateTimeString(),
            ':deleted_at' => $this->getDeletedAt()->toDateTimeString()
        ];
        $this->Connect();

        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO postres.departamentos values(
           :id,:nombre, :region,:estado, :created_at, :updated_at, :deleted_at) ";

        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE postres.departamentos SET
        nombre = :nombre, region = :region, estado= :estado,created_at = :created_at, updated_at = :updated_at,deleted_at= :deleted_at
        WHERE id = :id";

        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    function deleted(): ?bool
    {
        $this->setEstado( "Inactvo");
        return $this->update();
    }
}

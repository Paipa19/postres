<?php

namespace App\Models;

require_once ("AbstractDBConnection.php");
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');

use App\Enums\Estado;
use App\Interfaces\Model;
use App\Enums\EstadoUsuario;
use App\Enums\Rol;
use Exception;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;


class Usuarios extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $idUsuario;
    private int $numeroIdentificacion;
    private string $nombre;
    private string $apellido;
    private int $telefono;
    private string $correo;
    private Rol $rol;
    private ?string $contrasena;
    private Estado $estado;

    //Realaciones
    private ?array $DomicilioUsuario;
    private ?array $VentaUsuario;
    private ?array $PagoUsuario;

    /* Seguridad de Contraseña */
    const HASH = PASSWORD_DEFAULT;
    const COST = 10;

    /**
     * @param int|null $idUsuario
     * @param int $numeroIdentificacion
     * @param string $nombre
     * @param string $apellido
     * @param int $telefono
     * @param string $correo
     * @param string $rol
     * @param string|null $contrasena
     * @param string $estado
     */
    public function __construct(array $usuario = [])
    {
        parent::__construct();
        $this->setIdUsuario($usuario['idUsuario'] ?? null);
        $this->setNombre($usuario['nombre'] ?? '');
        $this->setApellido($usuario['apellido'] ?? '');
        $this->setNumeroIdentificacion($usuario['numeroIdentificacion'] ?? 0);
        $this->setTelefono($usuario['telefono'] ?? 0);
        $this->setCorreo($usuario['correo'] ?? '');
        $this->setRol($usuario['rol'] ?? Rol::EMPLEADO);
        $this->setContrasena($usuario['contrasena'] ?? null);
        $this->setEstado($usuario['estado'] ?? Estado::ACTIVO);

    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return int|null
     */
    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    /**
     * @param int|null $idUsuario
     */
    public function setIdUsuario(?int $idUsuario): void
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * @return int
     */
    public function getNumeroIdentificacion(): int
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param int $numeroIdentificacion
     */
    public function setNumeroIdentificacion(int $numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
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
    public function getApellido(): string
    {
        return $this->apellido;
    }

    /**
     * @param string $apellido
     */
    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    /**
     * @return int
     */
    public function getTelefono(): int
    {
        return $this->telefono;
    }

    /**
     * @param int $telefono
     */
    public function setTelefono(int $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getCorreo(): string
    {
        return $this->correo;
    }

    /**
     * @param string $correo
     */
    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol->toString();
    }

    /**
     * @param string $rol
     */
    public function setRol(null|string|Rol $rol): void
    {
        if(is_string($rol)){
            $this->rol = Rol::from($rol);
        }else{
            $this->rol = $rol;
        }
    }

    /**
     * @return string|null
     */
    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    /**
     * @param string|null $contrasena
     */
    public function setContrasena(?string $contrasena): void
    {
        $this->contrasena = $contrasena;
    }

    /**
     * @return Estado
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param Estado|null $estado
     */
    public function setEstado(null|string|Estado $estado): void
    {
        if(is_string($estado)){
            $this->estado = Estado::from($estado);
        }else{
            $this->estado = $estado;
        }
    }


    protected function save(string $query): ?bool
    {
        $hashPassword = password_hash($this->contrasena, self::HASH, ['cost' => self::COST]);
        $arrData = [

            ':idUsuario' => $this->getIdUsuario(),
            ':numeroIdentificacion' => $this->getNumeroIdentificacion(),
            ':nombre' => $this->getNombre(),
            ':apellido' => $this->getApellido(),
            ':telefono' => $this->getTelefono(),
            ':correo' => $this->getCorreo(),
            ':rol' => $this->getRol(),
            ':contrasena' => $hashPassword,
            ':estado' => $this->getEstado()

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */

    function insert(): ?bool
    {
        $query = "INSERT INTO postres.usuario Values(
           :idUsuario,:numeroIdentificacion,:nombre, :apellido,:telefono,
           :correo,:rol,:contrasena,:estado)";

        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE postres.usuario SET
        numeroIdentificacion = :numeroIdentificacion, nombre = :nombre, apellido = :apellido,
        telefono = :telefono, correo = :correo, rol = :rol, contrasena = :contrasena, estado= :estado,
        WHERE idUsuario = :idUsuario";

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

    /**
     * @param $query
     * @return Usuario|array
     * @throws Exception
     */

    static function search($query): ?array
    {
        try {
            $arrUsuario = array();
            $tmp = new Usuarios();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Usuario = new Usuarios($valor);
                    array_push($arrUsuario, $Usuario);
                    unset($Usuario);
                }
                return $arrUsuario;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }
    /**
     * @param int $idUsuario
     * @return Usuarios|null
     */
    public static function searchForId(int $idUsuario): ?Usuarios
    {
        try {
            if ($idUsuario > 0) {
                $tmpUsuario = new Usuario();
                $tmpUsuario->Connect();
                $getrow = $tmpUsuario->getRow("SELECT * FROM postres.usuario WHERE idUsuario =?", array($idUsuario));
                $tmpUsuario->Disconnect();
                return ($getrow) ? new Usuario($getrow) : null;
            } else {
                throw new Exception('Id de usuario Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function getAll(): ?array
    {
        return Usuario::search("SELECT * FROM postres.usuario");
    }

    /**
     * @param $numeroIdentificacion
     * @return bool
     * @throws Exception
     */

    public static function usuarioRegistrado($numeroIdentificacion): bool
    {
        $result = Usuarios::search("SELECT * FROM postres.usuario where numeroIdentificacion = " . $numeroIdentificacion);
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    public function nombresCompletos(): string
    {
        return $this->nombre . " " . $this->apellido;
    }

    public function __toString(): string
    {
        return "Nombre: $this->nombre, 
                Apellido: $this->apellido, 
                nombreIdentificacion: $this->numeroIdentificacion, 
                Telefono: $this->telefono, 
                correo: $this->correo, 
                rol: $this->rol, 
                estado:$this->estado";
    }
    public function login($user, $password): Usuario|String|null
    {

        try {
            $resultUsuario = Usuario::search("SELECT * FROM usuario WHERE user = '$user'");
            /* @var $resultUsuario Usuario[] */
            if (!empty($resultUsuario) && count($resultUsuario) >= 1) {
                if (password_verify($password, $resultUsuario[0]->getPassword())) {
                    if ($resultUsuario[0]->getEstado() == 'Activo') {
                        return $resultUsuario[0];
                    } else {
                        return "Usuario Inactivo";
                    }
                } else {
                    return "Contraseña Incorrecta";
                }
            } else {
                return "Usuario Incorrecto";
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
            return "Error en Servidor";
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'idUsuario' => $this->getIdUsuario(),
            'nombre' => $this->getNombre(),
            'apellido' => $this->getApellido(),
            'numeroIdentificacion' => $this->getNumeroIdentificacion(),
            'correo' => $this->getCorreo(),
            'telefono' => $this->getTelefono(),
            'rol' => $this->getRol(),
            'contrasena'=> $this->getContrasena(),
            'estado' => $this->getEstado(),

        ];
    }

}
<?php


class usuario
{
    private ?int $idUsuario;
    private int $numeroIdentificacion;
    private string $nombre;
    private string $apellido;
    private int $telefono;
    private string $correo;
    private string $rol;
    private ?string $contrasena;
    private string $estado;

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
    public function __construct(?int $idUsuario, int $numeroIdentificacion, string $nombre, string $apellido, int $telefono, string $correo, string $rol, ?string $contrasena, string $estado)
    {
        $this->idUsuario = $idUsuario;
        $this->numeroIdentificacion = $numeroIdentificacion;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->rol = $rol;
        $this->contrasena = $contrasena;
        $this->estado = $estado;
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
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
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
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }



    protected function save(string $query): bool
    {
        $arrData = [
            ':idUsuario' => $this->getIdUsuario(),
            ':numeroIdentificacion' => $this->getNumeroIdentificacion(),
            ':nombre' => $this->getNombre(),
            ':apellido' => $this->getApellido(),
            ':telefono' => $this->getTelefono(),
            ':correo' => $this->getCorreo(),
            ':rol' => $this->getRol(),
            ':contrasena' => $this->getContrasena(),
            ':estado' => $this->getEstado()
        ];

        $this->Connet();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    public function insert(): ?bool
    {
        $query = "INSERT INTO postres.usuario Values(
           :idUsuario,:numeroIdentidicacion,:nombre, :apellido,:telefono,
           :correo,:rol,:contrasena,:estado)";

        return $this->save($query);
    }


    public function update(): ?bool
    {
        $query = "UPDATE postres.usuario SET
        numeroIdentificacion = :numeroIdentificacion, nombre = :nombre, apellido = :apellido,
        telefono = :telefono, correo = :correo, rol = :rol, contrasena = :contrasena, estado= :estado,
        WHERE idUsuario = :idUsuario";

        return $this->save($query);
    }

    public function deleted(): bool

    {
        $this->setEstado(estado: "Inactvo");
        return $this->update();
    }


    public static function search($query): ?array
    {
        try {
            $arrUsuario = array();
            $tmp = new Usuario();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Usuario = new Usuario($valor);
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

    public static function searchForId(int $idUsuario): ?Usuario
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

    public static function getAll(): array
    {
        return Usuario::search("SELECT * FROM postres.usuario");
    }

    public static function usuarioRegistrado($numeroIdentificacion): bool
    {
        $result = Usuario::search("SELECT * FROM postres.usuario where numeroIdentificacion = " . $numeroIdentificacion);
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
    public function jsonSerialize(): array
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



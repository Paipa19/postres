<?php

class domicilio
{
   private int $idDomicilio;
   private string $direccion;
   private string $telefono;
   private int $municipios_id;
   private int $Usuario_id_Usuario;

    /**
     * @param int $idDomicilio
     * @param string $direccion
     * @param string $telefono
     * @param int $municipios_id
     * @param int $Usuario_id_Usuario
     */
    public function __construct(int $idDomicilio, string $direccion, string $telefono, int $municipios_id, int $Usuario_id_Usuario)
    {
        $this->idDomicilio = $idDomicilio;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->municipios_id = $municipios_id;
        $this->Usuario_id_Usuario = $Usuario_id_Usuario;
    }

    /**
     * @return int
     */
    public function getIdDomicilio(): int
    {
        return $this->idDomicilio;
    }

    /**
     * @param int $idDomicilio
     */
    public function setIdDomicilio(int $idDomicilio): void
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
        return $this->Usuario_id_Usuario;
    }

    /**
     * @param int $Usuario_id_Usuario
     */
    public function setUsuarioIdUsuario(int $Usuario_id_Usuario): void
    {
        $this->Usuario_id_Usuario = $Usuario_id_Usuario;
    }



}
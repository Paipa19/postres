<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\EstadoVenta;
use App\Interfaces\Model;



class Ventas extends AbstractDBConnection implements Model
{
    private ?int $idVenta;
    private string $numeroVenta;
    private Carbon $fecha;
    private string $total;
    private string $costoDomicilio;
    private EstadoVenta $estado;
    private int $domicilio_idDomicilio;
    private int $Usuario_idUsuario;


//Relaciones
    private ?Usuarios $Usuario;
    private ?Domicilios $Domicilio;

    private ?array $PagosVentas;
    private ?array $DetalleVentas;

    /**
     * @param int|null $idVenta
     * @param string $numeroVenta
     * @param Carbon $fecha
     * @param int $total
     * @param string $costo_domicilio
     * @param string $estado
     * @param int $Domicilio_id_Domicilio
     * @param int $Usuario_id_Usuario
     */
    public function __construct(array $venta = [])
    {
        parent::__construct();
        $this->setIdVenta($venta['idVenta'] ?? null);
        $this->setNumeroVenta($venta['numeroVenta'] ?? 0);
        $this->setFecha(!empty($venta['fecha'])?
            carbon::parse($venta['fecha']) : new carbon());
        $this->setTotal($venta['total'] ?? 0);
        $this->setCostoDomicilio($venta['costoDomicilio'] ?? 0);
        $this->setEstadoVenta($venta['estado'] ?? EstadoVenta::APROBADA);
        $this->setUsuarioIdUsuario($venta['Usuario_idUsuario'] ?? 0);
        $this->setDomicilioIdDomicilio($venta['domicilio_idDomicilio'] ?? 0);
    }

    public function __destruct(){
        if ($this->isConnected()){
            $this->Disconnect();
        }
    }

    /**
     * @return int|null
     */

    public function getIdVenta(): ?int
    {
        return $this->idVenta;
    }

    /**
     * @param int|null $idVenta
     */
    public function setIdVenta(?int $idVenta): void
    {
        $this->idVenta = $idVenta;
    }

    /**
     * @return string
     */
    public function getNumeroVenta(): string
    {
        return $this->numeroVenta;
    }

    /**
     * @param string $numeroVenta
     */
    public function setNumeroVenta(string $numeroVenta): void
    {
        $this->numeroVenta = $numeroVenta;
    }

    /**
     * @return Carbon
     */
    public function getFecha(): Carbon
    {
        return $this->fecha->locale('es');
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return string
     */
    public function getTotal(): string
    {
        return $this->total;
    }

    /**
     * @param string $total
     */
    public function setTotal(string $total): void
    {
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getCostoDomicilio(): string
    {
        return $this->costoDomicilio;
    }

    /**
     * @param string $costoDomicilio
     */
    public function setCostoDomicilio(string $costoDomicilio): void
    {
        $this->costoDomicilio = $costoDomicilio;
    }

    /**
     * @return int
     */
    public function getDomicilioIdDomicilio(): int
    {
        return $this->domicilio_idDomicilio;
    }

    /**
     * @param int $domicilio_idDomicilio
     */
    public function setDomicilioIdDomicilio(int $domicilio_idDomicilio): void
    {
        $this->domicilio_idDomicilio = $domicilio_idDomicilio;
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
     * @return string
     */
    public function getEstadoVenta(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param EstadoVenta|null $estado
     */
    public function setEstadoVenta(null|string|EstadoVenta $estado): void
    {
        if(is_string($estado)){
            $this->estado = EstadoVenta::from($estado);
        }else{
            $this->estado = $estado;
        }
    }



    /**
     * @return array|null
     */

    public function getPagosVentas(): ?array
    {
        return $this->PagosVentas;
    }

    /**
     * @param array|null $PagosVentas
     */
    public function setPagosVentas(?array $PagosVentas): void
    {
        $this->PagosVentas = $PagosVentas;
    }


    /**
     * @return array|null
     */

    public function getDetalleVentas(): ?array
    {
        return $this->DetalleVentas;
    }

    /**
     * @param array|null $DetalleVentas
     */
    public function setDetalleVentas(?array $DetalleVentas): void
    {
        $this->DetalleVentas = $DetalleVentas;
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

    /**
     * @return Domicilios|null
     */
    public function getDomicilio(): Domicilios|null
    {
        if (!empty($this->domicilio_idDomicilio)) {
            return Domicilios::searchForId($this->domicilio_idDomicilio) ?? new Domicilios();
        }
        return null;
    }



    protected function save(string $query): ?bool
    {

        $arrData = [
            ':idVenta' => $this->getIdVenta(),
            ':numeroVenta' => $this->getNumeroVenta(),
            ':fecha' => $this->getFecha()->toDateString(),
            ':total' => $this->getTotal(),
            ':costoDomicilio' => $this->getCostoDomicilio(),
            ':estado' => $this->getEstadoVenta() ,
            ':Usuario_idUsuario' => $this->getUsuarioIdUsuario(),
            ':domicilio_idDomicilio' => $this->getDomicilioIdDomicilio()
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO postres.venta VALUES (
        :idVenta, :numeroVenta, :fecha, :total, :costoDomicilio, :estado, :Usuario_idUsuario,:domicilio_idDomicilio )";
        return $this->save($query);
    }

    function update(): ?bool
    {

        $query = "UPDATE postres.venta SET
           numeroVenta = :numeroVenta, fecha = :fecha, total = :total,
           costoDomicilio = :costoDomicilio, estado = :estado, domicilio_idDomicilio = :domicilio_idDomicilio, Usuario_idUsuario, = :Usuario_idUsuario WHERE idVenta = idVenta";
        return $this -> save($query);

    }

    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
        $this->setEstadoVenta(EstadoVenta::NO_APROBADA); //cambia el estado de la venta
        return $this->update();
    }

    static function search($query): ?array
    {
        try {
            $arrVentas = array();
            $tmp = new Ventas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Ventas = new Ventas ($valor);
                    array_push($arrVentas, $Ventas);
                    unset($Ventas);
                }
                return $arrVentas;
            }
            return null;
        } catch (Exception $e) {
            GeneralFuntions::logFile('Exeption', $e);
        }
        return null;
    }

    static function searchForId(int $idVenta): ?object
    {
        try {
            if ($idVenta > 0) {
                $tmpVentas = new Ventas();
                $tmpVentas->Connect();
                $getrow = $tmpVentas->getrow("SELECT * FROM postres.venta WHERE idVenta =?", array($idVenta));
                $tmpVentas->Disconnect();
                return ($getrow) ? new Ventas($getrow) : null;
            } else {
                throw new  Exception('Id de ventas invalida');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }





    static function getAll(): ?array
    {
        return Ventas::search("SELECT * FROM postres.venta");
    }


    /**
     * @param $numeroVenta
     * @return bool
     * @throws Exception
     */
    public static function ventaRegistrada ($numeroVenta): bool
    {
        $result = Ventas::search("SELECT * FROM postres.venta where numeroVenta = " . $numeroVenta);
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return
            "numeroVenta: => $this->numeroVenta,
            fecha => $this->fecha,
            total' => $this->total,
            costoDomicilio => $this->costoDomicilio,
            estado: ".$this->getEstadoVenta().";
            domicilio_idDomicilio => $this->domicilio_idDomicilio,
            Usuario_idUsuario => $this->Usuario_idUsuario";
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'idVenta' => $this->getIdVenta(),
            'numeroVenta' => $this->getNumeroVenta(),
            'fecha' => $this->getFecha(),
            'total' => $this->getTotal(),
            'costoDomicilio' => $this->getCostoDomicilio(),
            'estado' => $this->getEstadoVenta(),
            'domicilio_idDomicilio' => $this->getDomicilio_idDomicilio(),
            'usuario_idUsuario' => $this->getUsuario_idUsuario(),

        ];
    }
}
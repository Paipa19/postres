<?php

namespace App\Models;

use App\Enums\EstadoPago;


use Carbon\Carbon;


class Pagos extends AbstractDBConnection
{

    private ?int $idPago;
    private string $abono;
    private string $saldo;
    private Carbon $fechaPago;
    private string $descuento;
    private EstadoPago $estado;
    private int $Venta_idVenta;
    private int $usuario_idUsuario;


    /*RELACIONES*/
    private ?Ventas $venta;
    private ?Usuarios $usuario;



    /**
     * @param int $idPago
     * @param string $abono
     * @param string $saldo
     * @param Carbon $fechaPago
     * @param string $descuento
     * @param string $estado
     * @param int $Venta_idVenta
     * @param int $usuario_idUsuario
     */
    public function __construct(array $pago=[])
    {

        parent::__construct();
        $this->setIdPago($pago['idPago'] ?? null);
        $this->setAbono($pago['abono'] ?? 0);
        $this->setSaldo($pago['saldo'] ?? 0);
        $this->setFechaPago(!empty($pago['fechaPago'])?
            carbon::parse($pago['fechaPago']) : new carbon());
        $this->setDescuento($pago['descuento'] ?? 0);
        $this->setEstadoPago($pago['estado'] ?? EstadoPago::CANCELADO);
        $this->setVentaIdVenta($pago['Venta_idVenta'] ?? 0);
        $this->setUsuarioIdUsuario ($pago['Usuario_idUsuario']?? 0);
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
    public function getIdPago(): ?int
    {
        return $this->idPago;
    }

    /**
     * @param int|null $idPago
     */
    public function setIdPago(?int $idPago): void
    {
        $this->idPago = $idPago;
    }

    /**
     * @return string
     */
    public function getAbono(): string
    {
        return $this->abono;
    }

    /**
     * @param string $abono
     */
    public function setAbono(string $abono): void
    {
        $this->abono = $abono;
    }

    /**
     * @return string
     */
    public function getSaldo(): string
    {
        return $this->saldo;
    }

    /**
     * @param string $saldo
     */
    public function setSaldo(string $saldo): void
    {
        $this->saldo = $saldo;
    }

    /**
     * @return Carbon
     */
    public function getFechaPago(): Carbon
    {
        return $this->fechaPago->locale('es');
    }

    /**
     * @param Carbon $fechaPago
     */
    public function setFechaPago(Carbon $fechaPago): void
    {
        $this->fechaPago = $fechaPago;
    }

    /**
     * @return string
     */
    public function getDescuento(): string
    {
        return $this->descuento;
    }

    /**
     * @param string $descuento
     */
    public function setDescuento(string $descuento): void
    {
        $this->descuento = $descuento;
    }

    /**
     * @return string
     */
    public function getEstadoPago(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param string|Estado|null $estado
     */
    public function setEstadoPago(null |string|EstadoPago $estado): void
    {
        if (is_string($estado)){
            $this->estado = EstadoPago::from($estado) ;
        }else{
            $this->estado = $estado;
        }
    }

    /**
     * @return int
     */
    public function getVentaIdVenta(): int
    {
        return $this->Venta_idVenta;
    }

    /**
     * @param int $Venta_idVenta
     */
    public function setVentaIdVenta(int $Venta_idVenta): void
    {
        $this->Venta_idVenta = $Venta_idVenta;
    }

    /**
     * @return int
     */
    public function getUsuarioIdUsuario(): int
    {
        return $this->usuario_idUsuario;
    }

    /**
     * @param int $usuario_idUsuario
     */
    public function setUsuarioIdUsuario(int $usuario_idUsuario): void
    {
        $this->usuario_idUsuario = $usuario_idUsuario;
    }





    /**
     * @return Ventas|null
     */
    public function getVenta(): ?Ventas
    {
        return $this->venta;
    }

    /**
     * @param Ventas|null $venta
     */
    public function setVenta(?Ventas $venta): void
    {
        $this->venta = $venta;
    }


    /**
     * @return Usuarios|null
     */
    public function getUsuario(): ?Usuarios
    {
        return $this->usuario;
    }

    /**
     * @param Usuarios|null $usuario
     */
    public function setUsuario(?Usuarios $usuario): void
    {
        $this->usuario = $usuario;
    }
  protected function save(string $query): ?bool
{
    $arrData = [
        ':idPago'=> $this->getIdPago(),
        ':abono' => $this->getAbono(),
        ':saldo'=> $this->getSaldo(),
        ':fechaPago'=>$this->getFechaPago()->toDateString(),
        ':descuento'=>$this->getDescuento(),
        ':estado'=>$this->getEstadoPago(),
        ':Venta_idVenta'=>$this->getVentaIdVenta(),
        ':Usuario_idUsuario'=>$this->getUsuarioIdUsuario(),

    ];
    $this->Connect();
    $result = $this->insertRow($query, $arrData);
    $this->Disconnect();
    return $result;
}
    function insert(): ?bool
    {
        $query = "INSERT INTO postres.pago Values(
           :idPago,:abono,:saldo, :fechaPago,:descuento,
           :estado, :Venta_idVenta, :Usuario_idUsuario)
           ";

        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE postres.pago SET
        abono = :abono, saldo = :saldo, fechaPago = :fechaPago,
        estado = :estado, Venta_idVenta = :Venta_idVenta, Usuario_idUsuario = :Usuario_idUsuario
        WHERE idPago = :idPago";

        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstadoPago( "Cancelado"); //cambia el estado del usuario
        return $this->update();
    }

    static function search($query): ?array
    {
        try {
            $arrPagos = array();
            $tmp = new Pagos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Pagos = new Pagos($valor);
                    array_push($arrPagos, $Pagos);
                    unset($Pago);
                }
                return $arrPagos;
            }
            return null;
        } catch (Exception $e) {
            \App\Models\GeneralFunctions::logFile('Exception', $e, 'error');
        }
        return null;
    }

    /**
     * @param int $idPago
     * @return Pagos
     * @throws Exception
     * @throws e
     */
    static function searchForIdPago(int $idPago): ?Pagos
    {
        try {
            if ($idPago > 0) {
                $tmpPago = new Pagos();
                $tmpPago->Connect();
                $getrow = $tmpPago->getRow("SELECT * FROM postres.pago WHERE idPago =?", array($idPago));
                $tmpPago->Disconnect();
                return ($getrow) ? new Pagos($getrow) : null;
            } else {
                throw new Exception('Id de pago Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function getAll(): ?array
    {
        return Pagos::search("SELECT * FROM postres.pago");
    }
    public static function pagoRegistrado($fechaPago): bool
    {
        $result = Pagos::search("SELECT * FROM postres.pago where fechaPago = '" . $fechaPago."'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    public function __toString(): string
    {
        return "abono: $this->abono, 
                saldo: $this->saldo, 
                fechaPago: $this->fechaPago, 
                descuento: $this->descuento, 
                estado: $this->estado";


    }

    /**
     * @inheritDoc
     */

    public function jsonSerialize(): array
    {
        return [
            'idPago' => $this->getIdPago(),
            'abono' => $this->getAbono(),
            'saldo' => $this->getSaldo(),
            'fechaPago' => $this->getFechaPago(),
            'descuento' => $this->getDescuento(),
            'estado' => $this->getEstadoPago(),
            'Venta_IdVenta'=>$this->getVentaIdVenta(),
            'Usuario_IdUsuario'=>$this->getUsuarioIdUsuario(),

        ];
    }

}
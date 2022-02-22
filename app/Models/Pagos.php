<?php

namespace App\Models;



class Pagos extends AbstractDBConnection implements \App\Interfaces\Model
{

    private int $idPago;
    private int $abono;
    private string $saldo;
    private Carbon $fechaPago;
    private string $descuento;
    private string $estado;
    private int $Venta_id_Venta;
    private int $Usuario_id_Usuario;

    /**
     * @param int $idPago
     * @param int $abono
     * @param string $saldo
     * @param Carbon $fechaPago
     * @param string $descuento
     * @param string $estado
     * @param int $Venta_id_Venta
     * @param int $Usuario_id_Usuario
     */
    public function __construct(array $pago=[])
    {
        parent::__construct();
        $this->setIdPago($pago['idpago'] ?? null);
        $this->setAbono($abono['abono'] ?? '');
        $this->setFechaPago($fechaPago['fechaPago'] ?? '');
        $this->setDescuento($descuento['descuento'] ?? '');
        $this->setEstado($estado['estado'] ?? '');

    }
    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return int
     */
    public function getIdPago(): int
    {
        return $this->idPago;
    }

    /**
     * @param int $idPago
     */
    public function setIdPago(int $idPago): void
    {
        $this->idPago = $idPago;
    }

    /**
     * @return int
     */
    public function getAbono(): int
    {
        return $this->abono;
    }

    /**
     * @param int $abono
     */
    public function setAbono(int $abono): void
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
        return $this->fechaPago;
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

    /**
     * @return int
     */
    public function getVentaIdVenta(): int
    {
        return $this->Venta_id_Venta;
    }

    /**
     * @param int $Venta_id_Venta
     */
    public function setVentaIdVenta(int $Venta_id_Venta): void
    {
        $this->Venta_id_Venta = $Venta_id_Venta;
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


    protected function save(string $query): ?bool
    {
        $arrData = [

            ':idPago'=> $this->idPago(),
            ':abono' => $this->abono(),
            ':saldo'=> $this->saldo(),
            'fechaPago'=>$this->fechaPago(),
            ':descuento'=>$this->descuento(),
            ':estado'=>$this->descuento(),
            'Venta_id_Venta' =>$this-> Venta_id_Venta(),
            'Usuario_id_Usuario'=> $this -> Usuario_id_Usuario(),
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
           :estado,:Venta_id_Venta,:Usuario_id_Usuario)";

        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE postres.pago SET
        abono = :abono, saldo = :saldo, fechaPago = :fechaPago,
        estado = :estado, Venta_id_Venta = :Venta_id_Venta, 
        WHERE idPago = :idPago";

        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado(estado: "Inactvo");
        return $this->update();
    }

    static function search($query): ?array
    {
        try {
            $arrPago = array();
            $tmp = new Pago();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Pago = new Pago($valor);
                    array_push($arrPago, $Pago);
                    unset($Pago);
                }
                return $arrPago;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $idPago): ?object
    {
        try {
            if ($idPago > 0) {
                $tmpPago = new Pago();
                $tmpPago->Connect();
                $getrow = $tmpPago->getRow("SELECT * FROM postres.Pago WHERE idPago =?", array($idPago));
                $tmpPago->Disconnect();
                return ($getrow) ? new Pago($getrow) : null;
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
        return Pago::search("SELECT * FROM postres.pago");
    }
    public static function pagoRegistrado($fechaPago): bool
    {
        $result = Pago::search("SELECT * FROM postres.Pago where fechaPago = " . $fechaPago);
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

    public function jsonSerialize(): mixed
    {
        return [
            'idPago' => $this->getIdPago(),
            'abono' => $this->getAbono(),
            'saldo' => $this->getSaldo(),
            'fechaPago' => $this->getFechaPago(),
            'descuento' => $this->getDescuento(),
            'estado' => $this->getEstado(),
            '$Venta_id_Venta' => $this->getVentaIdVenta(),
            'Usuario_id_Usuario' => $this->getUsuarioIdUsuario(),

        ];
    }

}
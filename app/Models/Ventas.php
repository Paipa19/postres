<?php

class Venta extends AbstractDBConnection implements Model
{
    private ?int $idVenta;
    private string $numeroVenta;
    private Carbon $fecha;
    private int $total;
    private string $costo_domicilio;
    private string $estado;
    private int $Domicilio_id_Domicilio;
    private int $Usuario_id_Usuario;


//Relaciones
    private ?array $PagosVentas;
    private ?array $DetalleVentasVentas;

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
    public function __construct(array $idVenta = [])
    {
        parent::__construct();
        $this->setIdVenta($idVenta['idVenta'] ?? null);
        $this->setNumeroVenta($numeroVenta['numeroVenta'] ?? '');
        $this->setFecha($fecha['fecha'] ?? '');
        $this->setTotal($total['total'] ?? '');
        $this->setCostoDomicilio($costo_domicilio['costo_domicilio'] ?? '');
        $this->setEstado($estado['estado'] ?? null);
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
        return $this->fecha;
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getCostoDomicilio(): string
    {
        return $this->costo_domicilio;
    }

    /**
     * @param string $costo_domicilio
     */
    public function setCostoDomicilio(string $costo_domicilio): void
    {
        $this->costo_domicilio = $costo_domicilio;
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

    public function getDetalleVentasVentas(): ?array
    {
        return $this->DetalleVentasVentas;
    }

    /**
     * @param array|null $DetalleVentasVentas
     */
    public function setDetalleVentasVentas(?array $DetalleVentasVentas): void
    {
        $this->DetalleVentasVentas = $DetalleVentasVentas;
    }


    protected function save(string $query): ?bool
    {

        $arrData = [
            ':idVenta' => $this->getIdVenta(),
            ':numeroVenta' => $this->getNumeroVenta(),
            ':fecha' => $this->getFecha(),
            ':total' => $this->getTotal(),
            ':costo_domicilio' => $this->getCostoDomicilio(),
            ':estado' => $this->getEstado(),
        ];
        $this->Connet();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        // TODO: Implement insert() method.
        $query = "INSERT INTO postres.venta VALUES (
        :idventas, :numeroVenta, :fecha, :total, :costo_domicilio, :estado                          
      )";
        return $this->save($query);
    }

    function update(): ?bool
    {

        $query = "UPDATE postres.venta SET
           numeroVenta = :numeroVenta, fecha = :fecha, total = :total,
           costo_domicilio = :costo_domicilio, estado = :estado WHERE idVenta = idVenta";
        return $this - save($query);

    }

    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
        $this->setEstado(EstadoGeneral::INACTIVO); //cambia el estado de la venta
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

    static function searchForId(int $idVentas): ?object
    {
        try {
            if ($idVentas > 0) {
                $tmpVentas = new Ventas();
                $tmpVentas->Connect();
                $getrow = $tmpVentas->getrow("SELECT * FROM postres.ventas WHERE idVentas =?", array($idVentas));
                $tmpVentas->Disconnet();
                return ($getrow) ? new Ventas($getrow) : null;
            } else {
                throw new  Exception('Id de ventas invalida');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }


    /**
     * @param $numeroVenta
     * @return bool
     * @throws Exception
     */
    public static function VentaRegistrada($numeroVenta): bool
    {
        $result = Venta::search("SELECT * FROM postres.ventas where documento = " . $numeroVenta);
        if (!empty($result) && count($result) > 0) {
            return true;
        } else {
            return false;
        }

    }



    static function getAll(): ?array
    {
        return Venta::search("SELECT * FROM postres.ventas");
    }



    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'idVenta' => $this->getIdVenta(),
            'numeroVenta' => $this->getNumeroVenta(),
            'fecha' => $this->getFecha(),
            'total' => $this->getTotal(),
            'costo_domicilio' => $this->getCostoDomicilio(),
            'estado' => $this->getEstado(),
        ];
    }
}

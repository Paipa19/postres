<?php

namespace App\Models;


use App\Enums\EstadoProducto;
use App\Interfaces\Model;

class Productos extends AbstractDBConnection implements Model
{
    private ?int $idProducto;
    private string $nombre;
    private string $descripcion;
    private string $valorUnitario;
    private EstadoProducto $estado;
    private int $stock;


    /* Relaciones */
    private ?array $DetalleVentaProductos;

    public function __construct(array $producto = [])
    {
        parent::__construct();
        $this->setIdProducto($producto['idProducto'] ?? null);
        $this->setNombre($producto['nombre'] ?? '');
        $this->setDescripcion($producto['descripcion'] ?? '');
        $this->setValorUnitario($producto['valorUnitario'] ?? 0);
        $this->setEstadoProducto($producto['estado'] ?? EstadoProducto::DISPONIBLE);
        $this->setStock($producto['stock'] ?? 0);
    }


    function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }


    /**
     * @return int|null
     */
    public function getIdProducto(): ?int
    {
        return $this->idProducto;
    }

    /**
     * @param int|null $idProducto
     */
    public function setIdProducto(?int $idProducto): void
    {
        $this->idProducto = $idProducto;
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
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return int
     */
    public function getValorUnitario(): string
    {
        return $this->valorUnitario;
    }

    /**
     * @param int $valorUnitario
     */
    public function setValorUnitario(string $valorUnitario): void
    {
        $this->valorUnitario = $valorUnitario;
    }


    /**
     * @return string
     */
    public function getEstadoProducto(): string
    {
        return $this->estadoProducto->toString();
    }

    /**
     * @param string |  $estadoProducto
     */
    public function setEstadoProducto(null|string|EstadoProducto $estadoProducto): void
    {
        if(is_string( $estadoProducto)){
            $this->estadoProducto = EstadoProducto::from( $estadoProducto);
        }else{
            $this->estadoProducto =  $estadoProducto;
        }
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return detalleVenta
     */
    public function __getdetalleVenta(): ?DetalleVentas
    {
        if (!empty($this->DetalleVentaProductos_id)) {
            $this->idProducto = Productos::searchForId($this->idProducto) ?? new Productos();
            return $this->idProducto;
        }
        return NULL;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idProducto' => $this->getIdProducto(),
            ':nombre' => $this->getNombre(),
            ':descripcion' => $this->getDescripcion(),
            ':valorUnitario' => $this->getValorUnitario(),
            ':estado' => $this->getEstadoProducto(),
            ':stock' => $this->getStock(),
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
        $query = "INSERT INTO postres.producto VALUES (:idProducto,:nombre,:descripcion,:valorUnitario,:estado, :stock)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): bool
    {
        $query = "UPDATE postres.producto SET 
             nombre = :nombre, descripcion = :descripcion, valorUnitario= :valorUnitario,estado= :estado ,stock= :stock
              WHERE idProducto = :idProducto";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstadoProducto("No Disponible");
        return $this->update();
    }

    /**
     * @param $query
     * @return Productos|array
     * @throws Exception
     */
    public static function search($query): ?array
    {
        try {
            $arrProductos = array();
            $tmp = new Productos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Producto = new Productos($valor);
                array_push($arrProductos, $Producto);
                unset($Producto);
            }
            return $arrProductos;
        } catch (Exception $e) {
            \App\Models\GeneralFunctions::logFile('Exception', $e, 'error');
        }
        return null;
    }

    /**
     * @param int $idProducto
     * @return Productos
     * @throws Exception
     * @throws e
     */
    public static function searchForID(int $idProducto): ?Productos
    {
        try {
            if ($idProducto > 0) {
                $tmpProducto = new Productos();
                $tmpProducto->Connect();
                $getrow = $tmpProducto->getRow("SELECT * FROM postres.producto WHERE idProducto =?", array($idProducto));
                $tmpProducto->Disconnect();
                return ($getrow) ? new Productos($getrow) : null;
            } else {
                throw new Exception('Id de producto Invalido');
            }
        } catch (Exception $e) {
           GeneralFunctions::logFile('Exeption', $e, 'error');
        }
        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll(): ?array
    {
        return Productos::search("SELECT * FROM postres.producto");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */

    public static function productoRegistrado($nombre): bool
    {
        $result = Productos::search("SELECT * FROM postres.producto where nombre = '" . $nombre . "'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }


    public function __toString(): string
    {
        return "nombre $this->nombre,
          descripcion: $this->descripcion,
          valorUnitario: $this->valorUnitario,
          estado: ".$this->getEstadoProducto().";
          stock: $this->stock";
    }

    public function substractStock(int $quantity)
    {
        $this->setStock($this->getStock() - $quantity);
        $result = $this->update();
        if ($result == false) {
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }

    public function addStock(int $quantity)
    {
        $this->setStock($this->getStock() + $quantity);
        $result = $this->update();
        if ($result == false) {
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }

    public function jsonSerialize(): array
    {
        return [
            'idProducto' => $this->getIdProducto(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'valorUnitario' => $this->getValorUnitario(),
            'estado' => $this->getEstado(),
            'stock' => $this->getStock(),
        ];
    }
}

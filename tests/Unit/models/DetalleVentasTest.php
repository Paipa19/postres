<?php

namespace Tests\Unit\models;


use App\Models\DetalleVentas;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
class DetalleVentasTest extends TestCase
{


    public function testInsert()
    {
        $DetalleVenta = new DetalleVentas([
                'idDetalleVenta' => null,
                'cantidad' => 3,
                'fechaVencimiento' => Carbon::parse('22-12-21')->format('d-m-Y'),
                'numDetalleVenta' => null,
                'Venta_idVenta' => 2,
                'Producto_idProducto'=> 1],
        );

        $DetalleVenta->insert();
        $this->assertSame(true, $DetalleVenta->detalleVentaRegistrado(3));
    }

}

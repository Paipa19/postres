<?php

namespace Tests\Unit\models;


use App\Models\DetalleVentas;
use App\Controllers\DetalleVentasController;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
class DetalleVentasTest extends TestCase
{


    public function testInsert()
    {
        $DetalleVenta = new DetalleVentas([
                'idDetalleVenta' => 2,
                'cantidad' => 3,
                'fechaVencimiento' => Carbon::parse('22-12-21')->format('d-m-Y'),
                'numDetalleVenta' => 1,
                'Venta_idVenta' => 2,
                'Producto_idProducto'=> 1,
                ]
        );

        $DetalleVenta->insert();
        $this->assertSame(true, $DetalleVenta->detalleVentaRegistrado(3));
    }

}

<?php

namespace Tests\Unit\models;


use App\Models\DetalleVentas;
use PHPUnit\Framework\TestCase;
use Tests\Models\Carbon;

class DetalleVentasTest extends TestCase
{


    public function testInsert()
    {
        $DetalleVenta = new DetalleVentas(['idDetalleVenta' => null,
                'cantidad' => 1555,
                'fechaVencimiento' => Carbon::parse('02-12-21')->format('d-m-Y'),
                'numDetalleventa' => 12345,
                'Venta_idVenta' => 321,
                'producto_idProducto'=> 123],
        );

        $DetalleVenta->insert();
        $this->assertSame(true, $DetalleVenta->detalleVentaRegistrado(12345));
    }

}

<?php

namespace Tests\Unit\models;

use App\Enums\EstadoVenta;
use App\Models\Ventas;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;


class VentasTest extends TestCase
{


    public function testInsert()
    {
        $Venta = new Ventas([
                'IdVenta' => null,
                'numeroVenta' => 1,
                'fecha' => Carbon::parse('02-12-21')->format('d-m-Y'),
                'total' => '120000',
                'costoDomicilio' => '20000',
                'estado'=> EstadoVenta::APROBADA,
                'domicilio_idDomicilio'=>52,
                'Usuario_idUsuario' => 1
            ]
        );

        $Venta->insert();
        $this->assertSame(true, $Venta->ventaRegistrada(1));
    }

}


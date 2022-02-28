<?php

namespace Tests\Unit\models;

use App\Enums\EstadoPago;
use App\Models\Pagos;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PagosTest extends TestCase
{

    public function testInsert()
    {
        $Pago = new Pagos([
            'idPago' => null,
            'abono' => 2500,
            'saldo' => 2500,
            'fechaPago' => Carbon::parse('02-12-21')->format('d-m-Y'),
            'descuento' => 500,
                 'estado' =>EstadoPago::CANCELADO,
                 'Venta_idVenta'=> 2,
                 'Usuario_idUsuario' =>1,
        ]
        );
        $Pago->insert();
            $this->assertSame( true, $Pago->pagoRegistrado('02-12-21'));
    }

}

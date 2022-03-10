<?php

namespace Tests\Unit\models;


use App\Models\Domicilios;
use PHPUnit\Framework\TestCase;

class DomiciliosTest extends TestCase
{


    public function testInsert()
    {
        $Domicilio = new Domicilios([
                'idDomicilio' => null,
                'direccion' => 'Cra17con1',
                'telefono' => '3137737082',
                'municipios_id' => 99775,
                'Usuario_idUsuario' => 1,
               ]
        );

        $Domicilio->insert();
        $this->assertSame(true, $Domicilio->domicilioRegistrado('3137737082'));
    }

}

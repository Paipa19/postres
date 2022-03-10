<?php

namespace Tests\Unit\models;

use App\Enums\Estado;
use App\Models\Municipios;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

require_once (__DIR__.'/../../../vendor/autoload.php');

class MunicipiosTest extends TestCase
{


    public function testInsert()
    {
        $Municipio = new Municipios(['id' => null,
                'nombre' => 'Sogamoso',
                'departamento_id' => 100,
                'acortado' => 'Sog',
                'estado' => Estado::ACTIVO,
                'created_at' => Carbon::parse('02-12-21')->format('d-m-Y'),
                'updated_at' => Carbon::parse('02-12-21')->format('d-m-Y'),
                'deleted_at' => Carbon::parse('02-12-21')->format('d-m-Y'),]
        );

        $Municipio->insert();
        $this->assertSame(true, $Municipio->MunicipioRegistrado('Sogamoso'));
    }

}
